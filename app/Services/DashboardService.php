<?php

namespace App\Services;

use App\Models\Task;
use App\Models\TaskActivity;
use App\Models\User;
use Carbon\Carbon;

class DashboardService
{
    /**
     * Get aggregated statistics and charts data for user's dashboard.
     *
     * @return array<string, mixed>
     */
    public function getDashboardData(User $user, ?int $projectId = null): array
    {
        $baseQuery = Task::accessibleBy($user);

        if ($projectId) {
            $baseQuery->where('project_id', $projectId);
        }

        $totalTasks = (clone $baseQuery)->count();
        $completedTasks = (clone $baseQuery)->where('status', 'done')->count();
        $inProgressTasks = (clone $baseQuery)->where('status', 'in_progress')->count();
        $todoTasks = (clone $baseQuery)->where('status', 'todo')->count();
        $overdueTasks = (clone $baseQuery)->overdue()->count();
        $dueSoonTasks = (clone $baseQuery)->dueSoon()->count();

        $completionRate = $totalTasks > 0 ? (int) round(($completedTasks / $totalTasks) * 100) : 0;

        // Weekly productivity: last 7 days
        $weeklyDays = [];
        $weeklyCompleted = [];
        $weeklyCreated = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $dayLabel = $date->isoFormat('ddd, D MMM');
            $dateString = $date->toDateString();

            $weeklyDays[] = $dayLabel;

            $completedCount = (clone $baseQuery)
                ->where('status', 'done')
                ->whereNotNull('completed_at')
                ->whereDate('completed_at', $dateString)
                ->count();

            $createdCount = (clone $baseQuery)
                ->whereDate('created_at', $dateString)
                ->count();

            $weeklyCompleted[] = $completedCount;
            $weeklyCreated[] = $createdCount;
        }

        // Priority breakdown
        $priorityBreakdown = [
            'high' => (clone $baseQuery)->where('priority', 'high')->count(),
            'medium' => (clone $baseQuery)->where('priority', 'medium')->count(),
            'low' => (clone $baseQuery)->where('priority', 'low')->count(),
        ];

        // Recent tasks
        $recentTasks = (clone $baseQuery)
            ->with(['project', 'category', 'assignee'])
            ->latest()
            ->take(5)
            ->get();

        // Upcoming due tasks
        $upcomingTasks = (clone $baseQuery)
            ->with(['project', 'category', 'assignee'])
            ->where('status', '!=', 'done')
            ->whereNotNull('due_date')
            ->where('due_date', '>=', Carbon::today()->toDateString())
            ->orderBy('due_date', 'asc')
            ->take(5)
            ->get();

        // Recent activities on accessible tasks
        $accessibleTaskIds = (clone $baseQuery)->pluck('id');
        $recentActivities = TaskActivity::whereIn('task_id', $accessibleTaskIds)
            ->with(['user', 'task'])
            ->latest()
            ->take(8)
            ->get();

        return [
            'totalTasks' => $totalTasks,
            'completedTasks' => $completedTasks,
            'inProgressTasks' => $inProgressTasks,
            'todoTasks' => $todoTasks,
            'overdueTasks' => $overdueTasks,
            'dueSoonTasks' => $dueSoonTasks,
            'completionRate' => $completionRate,
            'weeklyDays' => $weeklyDays,
            'weeklyCompleted' => $weeklyCompleted,
            'weeklyCreated' => $weeklyCreated,
            'priorityBreakdown' => $priorityBreakdown,
            'recentTasks' => $recentTasks,
            'upcomingTasks' => $upcomingTasks,
            'recentActivities' => $recentActivities,
        ];
    }
}
