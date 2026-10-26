<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\Task;
use App\Models\TaskActivity;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TaskService
{
    /**
     * Create a new task and log initial activity.
     */
    public function createTask(array $data, User $creator): Task
    {
        return DB::transaction(function () use ($data, $creator) {
            $data['created_by'] = $creator->id;

            if (($data['status'] ?? 'todo') === 'done') {
                $data['completed_at'] = now();
            } else {
                $data['completed_at'] = null;
            }

            $task = Task::create($data);

            // Log activity
            TaskActivity::create([
                'task_id' => $task->id,
                'user_id' => $creator->id,
                'action' => 'created',
                'old_value' => null,
                'new_value' => $task->title,
            ]);

            // Notify assignee if assigned to someone else
            if (! empty($task->assigned_to) && $task->assigned_to !== $creator->id) {
                Notification::create([
                    'user_id' => $task->assigned_to,
                    'task_id' => $task->id,
                    'type' => 'task_assigned',
                    'message' => "Anda telah ditugaskan pada tugas: '{$task->title}' oleh {$creator->name}.",
                ]);
            }

            return $task;
        });
    }

    /**
     * Update an existing task, logging changed fields to task_activities.
     */
    public function updateTask(Task $task, array $data, User $updater): Task
    {
        return DB::transaction(function () use ($task, $data, $updater) {
            $oldStatus = $task->status;
            $oldPriority = $task->priority;
            $oldAssignee = $task->assigned_to;
            $oldDueDate = $task->due_date ? $task->due_date->format('Y-m-d') : null;

            $newStatus = $data['status'] ?? $oldStatus;
            $newPriority = $data['priority'] ?? $oldPriority;
            $newAssignee = array_key_exists('assigned_to', $data) ? $data['assigned_to'] : $oldAssignee;
            $newDueDate = ! empty($data['due_date']) ? Carbon::parse($data['due_date'])->format('Y-m-d') : null;

            // Handle completed_at timestamp
            if ($newStatus === 'done' && $oldStatus !== 'done') {
                $data['completed_at'] = now();
            } elseif ($newStatus !== 'done' && $oldStatus === 'done') {
                $data['completed_at'] = null;
            }

            $task->update($data);

            // Record activities for relevant changes
            if ($oldStatus !== $newStatus) {
                TaskActivity::create([
                    'task_id' => $task->id,
                    'user_id' => $updater->id,
                    'action' => 'status_updated',
                    'old_value' => $oldStatus,
                    'new_value' => $newStatus,
                ]);
            }

            if ($oldPriority !== $newPriority) {
                TaskActivity::create([
                    'task_id' => $task->id,
                    'user_id' => $updater->id,
                    'action' => 'priority_updated',
                    'old_value' => $oldPriority,
                    'new_value' => $newPriority,
                ]);
            }

            if ($oldDueDate !== $newDueDate) {
                TaskActivity::create([
                    'task_id' => $task->id,
                    'user_id' => $updater->id,
                    'action' => 'due_date_updated',
                    'old_value' => $oldDueDate,
                    'new_value' => $newDueDate,
                ]);
            }

            if ((int) $oldAssignee !== (int) $newAssignee) {
                $oldAssigneeUser = $oldAssignee ? User::find($oldAssignee)?->name : 'Unassigned';
                $newAssigneeUser = $newAssignee ? User::find($newAssignee)?->name : 'Unassigned';

                TaskActivity::create([
                    'task_id' => $task->id,
                    'user_id' => $updater->id,
                    'action' => 'assigned',
                    'old_value' => $oldAssigneeUser,
                    'new_value' => $newAssigneeUser,
                ]);

                if ($newAssignee && (int) $newAssignee !== (int) $updater->id) {
                    Notification::create([
                        'user_id' => $newAssignee,
                        'task_id' => $task->id,
                        'type' => 'task_assigned',
                        'message' => "Anda telah ditugaskan pada tugas: '{$task->title}' oleh {$updater->name}.",
                    ]);
                }
            }

            return $task->fresh();
        });
    }

    /**
     * Update task status directly (e.g. from Kanban drag-and-drop or quick select).
     */
    public function updateStatus(Task $task, string $status, User $updater): Task
    {
        return $this->updateTask($task, ['status' => $status], $updater);
    }
}
