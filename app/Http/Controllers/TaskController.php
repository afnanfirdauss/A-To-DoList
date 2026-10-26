<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Category;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Services\TaskService;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TaskController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of tasks with filters, search, sorting, and pagination.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        $query = Task::accessibleBy($user)
            ->with(['project', 'category', 'assignee', 'creator']);

        // Search by title or description
        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', $searchTerm)
                    ->orWhere('description', 'like', $searchTerm);
            });
        }

        // Filter by Status
        if ($request->filled('status') && in_array($request->status, ['todo', 'in_progress', 'done'])) {
            $query->where('status', $request->status);
        }

        // Filter by Priority
        if ($request->filled('priority') && in_array($request->priority, ['low', 'medium', 'high'])) {
            $query->where('priority', $request->priority);
        }

        // Filter by Project
        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        // Filter by Category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by Deadline condition
        if ($request->filled('deadline_filter')) {
            if ($request->deadline_filter === 'overdue') {
                $query->overdue();
            } elseif ($request->deadline_filter === 'due_soon') {
                $query->dueSoon();
            } elseif ($request->deadline_filter === 'no_deadline') {
                $query->whereNull('due_date');
            }
        }

        // Sorting
        $sortBy = $request->get('sort', 'created_at_desc');
        switch ($sortBy) {
            case 'due_date_asc':
                $query->orderByRaw('CASE WHEN due_date IS NULL THEN 1 ELSE 0 END, due_date ASC');
                break;
            case 'due_date_desc':
                $query->orderByRaw('CASE WHEN due_date IS NULL THEN 1 ELSE 0 END, due_date DESC');
                break;
            case 'priority_desc':
                $query->orderByRaw("CASE priority WHEN 'high' THEN 1 WHEN 'medium' THEN 2 WHEN 'low' THEN 3 ELSE 4 END");
                break;
            case 'created_at_asc':
                $query->oldest();
                break;
            default:
                $query->latest();
                break;
        }

        $tasks = $query->paginate(10)->withQueryString();

        $projects = Project::where('user_id', $user->id)
            ->orWhereHas('members', fn ($q) => $q->where('users.id', $user->id))
            ->orderBy('name')
            ->get();

        $categories = Category::where('user_id', $user->id)->orderBy('name')->get();

        return view('tasks.index', compact('tasks', 'projects', 'categories'));
    }

    /**
     * Show the form for creating a new task.
     */
    public function create(Request $request): View
    {
        $user = $request->user();

        $projects = Project::with('members')->where('user_id', $user->id)
            ->orWhereHas('members', fn ($q) => $q->where('users.id', $user->id))
            ->orderBy('name')
            ->get();

        $categories = Category::where('user_id', $user->id)->orderBy('name')->get();

        // Potential assignees across user's accessible projects plus the user
        $assigneeUserIds = [$user->id];
        foreach ($projects as $project) {
            $assigneeUserIds[] = $project->user_id;
            foreach ($project->members as $member) {
                $assigneeUserIds[] = $member->id;
            }
        }
        $assignees = User::whereIn('id', array_unique($assigneeUserIds))->orderBy('name')->get();

        $preselectedProjectId = $request->query('project_id');

        return view('tasks.create', compact('projects', 'categories', 'assignees', 'preselectedProjectId'));
    }

    /**
     * Store a newly created task in storage.
     */
    public function store(StoreTaskRequest $request, TaskService $taskService): RedirectResponse
    {
        $task = $taskService->createTask($request->validated(), $request->user());

        return redirect()->route('tasks.show', $task)->with('success', 'Tugas berhasil dibuat.');
    }

    /**
     * Display the specified task.
     */
    public function show(Task $task): View
    {
        $this->authorize('view', $task);

        $task->load([
            'project.owner',
            'project.members',
            'category',
            'creator',
            'assignee',
            'comments.user',
            'activities.user',
        ]);

        return view('tasks.show', compact('task'));
    }

    /**
     * Show the form for editing the specified task.
     */
    public function edit(Request $request, Task $task): View
    {
        $this->authorize('update', $task);

        $user = $request->user();

        $projects = Project::with('members')->where('user_id', $user->id)
            ->orWhereHas('members', fn ($q) => $q->where('users.id', $user->id))
            ->orderBy('name')
            ->get();

        $categories = Category::where('user_id', $user->id)->orderBy('name')->get();

        $assigneeUserIds = [$user->id];
        foreach ($projects as $project) {
            $assigneeUserIds[] = $project->user_id;
            foreach ($project->members as $member) {
                $assigneeUserIds[] = $member->id;
            }
        }
        $assignees = User::whereIn('id', array_unique($assigneeUserIds))->orderBy('name')->get();

        return view('tasks.edit', compact('task', 'projects', 'categories', 'assignees'));
    }

    /**
     * Update the specified task in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task, TaskService $taskService): RedirectResponse
    {
        $this->authorize('update', $task);

        $taskService->updateTask($task, $request->validated(), $request->user());

        return redirect()->route('tasks.show', $task)->with('success', 'Tugas berhasil diperbarui.');
    }

    /**
     * Update the status of the specified task (for Kanban and quick toggles).
     */
    public function updateStatus(Request $request, Task $task, TaskService $taskService): JsonResponse|RedirectResponse
    {
        $this->authorize('update', $task);

        $validated = $request->validate([
            'status' => ['required', 'in:todo,in_progress,done'],
        ]);

        $task = $taskService->updateStatus($task, $validated['status'], $request->user());

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Status tugas berhasil diperbarui.',
                'task' => [
                    'id' => $task->id,
                    'status' => $task->status,
                    'completed_at' => $task->completed_at ? $task->completed_at->toISOString() : null,
                ],
            ]);
        }

        return back()->with('success', 'Status tugas berhasil diperbarui.');
    }

    /**
     * Remove the specified task from storage.
     */
    public function destroy(Task $task): RedirectResponse
    {
        $this->authorize('delete', $task);

        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Tugas berhasil dihapus.');
    }
}
