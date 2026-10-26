<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    /**
     * Determine whether the user can view any tasks.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the task.
     */
    public function view(User $user, Task $task): bool
    {
        if ($task->created_by === $user->id || $task->assigned_to === $user->id) {
            return true;
        }

        if ($task->project_id && $task->project) {
            return $task->project->hasAccess($user);
        }

        return false;
    }

    /**
     * Determine whether the user can create tasks.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the task.
     */
    public function update(User $user, Task $task): bool
    {
        if ($task->created_by === $user->id || $task->assigned_to === $user->id) {
            return true;
        }

        if ($task->project_id && $task->project) {
            return $task->project->hasAccess($user);
        }

        return false;
    }

    /**
     * Determine whether the user can delete the task.
     */
    public function delete(User $user, Task $task): bool
    {
        // Only creator or project owner can delete task
        if ($task->created_by === $user->id) {
            return true;
        }

        if ($task->project_id && $task->project) {
            return $task->project->isOwner($user);
        }

        return false;
    }
}
