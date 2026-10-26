<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KanbanController extends Controller
{
    /**
     * Display the Kanban Board.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        $query = Task::accessibleBy($user)
            ->with(['project', 'category', 'assignee']);

        // Filter by Project
        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        // Filter by Priority
        if ($request->filled('priority') && in_array($request->priority, ['low', 'medium', 'high'])) {
            $query->where('priority', $request->priority);
        }

        // Filter by Assignee
        if ($request->filled('assigned_to')) {
            $query->where('assigned_to', $request->assigned_to);
        }

        $allTasks = $query->orderByRaw('CASE WHEN due_date IS NULL THEN 1 ELSE 0 END, due_date ASC')->get();

        $todoTasks = $allTasks->where('status', 'todo')->values();
        $inProgressTasks = $allTasks->where('status', 'in_progress')->values();
        $doneTasks = $allTasks->where('status', 'done')->values();

        $projects = Project::where('user_id', $user->id)
            ->orWhereHas('members', fn ($q) => $q->where('users.id', $user->id))
            ->orderBy('name')
            ->get();

        $assigneeUserIds = [$user->id];
        foreach ($projects as $project) {
            $assigneeUserIds[] = $project->user_id;
            foreach ($project->members as $member) {
                $assigneeUserIds[] = $member->id;
            }
        }
        $assignees = User::whereIn('id', array_unique($assigneeUserIds))->orderBy('name')->get();

        return view('kanban.index', compact(
            'todoTasks',
            'inProgressTasks',
            'doneTasks',
            'projects',
            'assignees'
        ));
    }
}
