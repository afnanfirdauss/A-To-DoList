<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\Task;
use Carbon\Carbon;

class DeadlineNotificationService
{
    /**
     * Check active tasks for approaching deadlines and overdue statuses, sending in-app notifications.
     */
    public function checkAndSendNotifications(): int
    {
        $today = Carbon::today();
        $tomorrow = Carbon::tomorrow();
        $notificationsCreated = 0;

        // 1. Check Overdue Tasks
        $overdueTasks = Task::with(['creator', 'assignee'])
            ->where('status', '!=', 'done')
            ->whereNotNull('due_date')
            ->where('due_date', '<', $today->toDateString())
            ->get();

        foreach ($overdueTasks as $task) {
            $recipients = array_unique(array_filter([$task->assigned_to, $task->created_by]));

            foreach ($recipients as $userId) {
                // Deduplicate: check if overdue notification already sent today for this task
                $exists = Notification::where('user_id', $userId)
                    ->where('task_id', $task->id)
                    ->where('type', 'overdue')
                    ->whereDate('created_at', $today->toDateString())
                    ->exists();

                if (! $exists) {
                    Notification::create([
                        'user_id' => $userId,
                        'task_id' => $task->id,
                        'type' => 'overdue',
                        'message' => "Tugas '{$task->title}' telah melewati batas deadline ({$task->due_date->format('d M Y')}). Segera selesaikan!",
                    ]);
                    $notificationsCreated++;
                }
            }
        }

        // 2. Check Approaching Deadline Tasks (due today or tomorrow)
        $dueSoonTasks = Task::with(['creator', 'assignee'])
            ->where('status', '!=', 'done')
            ->whereNotNull('due_date')
            ->whereBetween('due_date', [$today->toDateString(), $tomorrow->toDateString()])
            ->get();

        foreach ($dueSoonTasks as $task) {
            $recipients = array_unique(array_filter([$task->assigned_to, $task->created_by]));

            foreach ($recipients as $userId) {
                // Deduplicate: check if approaching notification already sent today for this task
                $exists = Notification::where('user_id', $userId)
                    ->where('task_id', $task->id)
                    ->where('type', 'deadline_approaching')
                    ->whereDate('created_at', $today->toDateString())
                    ->exists();

                if (! $exists) {
                    $dueText = $task->due_date->isToday() ? 'HARI INI' : 'besok (' . $task->due_date->format('d M Y') . ')';

                    Notification::create([
                        'user_id' => $userId,
                        'task_id' => $task->id,
                        'type' => 'deadline_approaching',
                        'message' => "Pengingat: Tugas '{$task->title}' akan jatuh tempo {$dueText}.",
                    ]);
                    $notificationsCreated++;
                }
            }
        }

        return $notificationsCreated;
    }
}
