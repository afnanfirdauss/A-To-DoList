<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Notification;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of projects accessible by the user.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        $projects = Project::where('user_id', $user->id)
            ->orWhereHas('members', fn ($q) => $q->where('users.id', $user->id))
            ->with(['owner', 'members', 'tasks'])
            ->latest()
            ->paginate(9);

        return view('projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new project.
     */
    public function create(): View
    {
        return view('projects.create');
    }

    /**
     * Store a newly created project in storage.
     */
    public function store(StoreProjectRequest $request): RedirectResponse
    {
        $project = $request->user()->ownedProjects()->create($request->validated());

        return redirect()->route('projects.show', $project)->with('success', 'Proyek berhasil dibuat.');
    }

    /**
     * Display the specified project with tasks and team members.
     */
    public function show(Request $request, Project $project): View
    {
        $this->authorize('view', $project);

        $project->load(['owner', 'members']);

        // Tasks query for this project
        $tasksQuery = $project->tasks()->with(['category', 'assignee', 'creator']);

        if ($request->filled('status') && in_array($request->status, ['todo', 'in_progress', 'done'])) {
            $tasksQuery->where('status', $request->status);
        }

        if ($request->filled('priority') && in_array($request->priority, ['low', 'medium', 'high'])) {
            $tasksQuery->where('priority', $request->priority);
        }

        $tasks = $tasksQuery->orderByRaw('CASE WHEN due_date IS NULL THEN 1 ELSE 0 END, due_date ASC')->get();

        $progress = $project->calculateProgress();
        $isOwner = $project->isOwner($request->user());

        return view('projects.show', compact('project', 'tasks', 'progress', 'isOwner'));
    }

    /**
     * Show the form for editing the specified project.
     */
    public function edit(Project $project): View
    {
        $this->authorize('update', $project);

        return view('projects.edit', compact('project'));
    }

    /**
     * Update the specified project in storage.
     */
    public function update(UpdateProjectRequest $request, Project $project): RedirectResponse
    {
        $this->authorize('update', $project);

        $project->update($request->validated());

        return redirect()->route('projects.show', $project)->with('success', 'Proyek berhasil diperbarui.');
    }

    /**
     * Remove the specified project from storage.
     */
    public function destroy(Project $project): RedirectResponse
    {
        $this->authorize('delete', $project);

        $project->delete();

        return redirect()->route('projects.index')->with('success', 'Proyek berhasil dihapus.');
    }

    /**
     * Add a member to the project by registered user email.
     */
    public function addMember(Request $request, Project $project): RedirectResponse
    {
        $this->authorize('manageMembers', $project);

        $validated = $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ], [
            'email.required' => 'Email pengguna wajib diisi.',
            'email.exists' => 'Pengguna dengan email ini belum terdaftar di TaskFlow.',
        ]);

        $targetUser = User::where('email', $validated['email'])->firstOrFail();

        // Check if user is owner
        if ($project->isOwner($targetUser)) {
            return back()->withErrors(['email' => 'Pengguna ini sudah menjadi pemilik (owner) proyek.']);
        }

        // Check if user is already a member
        if ($project->members()->where('users.id', $targetUser->id)->exists()) {
            return back()->withErrors(['email' => 'Pengguna ini sudah terdaftar sebagai anggota proyek.']);
        }

        $project->members()->attach($targetUser->id, ['role' => 'member']);

        // In-app notification
        Notification::create([
            'user_id' => $targetUser->id,
            'task_id' => null,
            'type' => 'project_invitation',
            'message' => "Anda telah ditambahkan sebagai anggota proyek '{$project->name}' oleh {$request->user()->name}.",
        ]);

        return back()->with('success', "Anggota {$targetUser->name} ({$targetUser->email}) berhasil ditambahkan ke proyek.");
    }

    /**
     * Remove a member from the project.
     */
    public function removeMember(Project $project, User $user): RedirectResponse
    {
        $this->authorize('manageMembers', $project);

        // Cannot remove the owner
        if ($project->isOwner($user)) {
            return back()->with('error', 'Pemilik proyek tidak dapat dihapus.');
        }

        $project->members()->detach($user->id);

        // Unassign user's tasks in this project
        $project->tasks()->where('assigned_to', $user->id)->update(['assigned_to' => null]);

        return back()->with('success', "Anggota {$user->name} berhasil dihapus dari proyek.");
    }
}
