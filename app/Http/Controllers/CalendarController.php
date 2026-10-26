<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CalendarController extends Controller
{
    /**
     * Display the deadline calendar page.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        $projects = Project::where('user_id', $user->id)
            ->orWhereHas('members', fn ($q) => $q->where('users.id', $user->id))
            ->orderBy('name')
            ->get();

        return view('calendar.index', compact('projects'));
    }

    /**
     * Provide JSON feed of deadline events formatted for FullCalendar.
     */
    public function events(Request $request): JsonResponse
    {
        $user = $request->user();

        $query = Task::accessibleBy($user)
            ->with(['project', 'category'])
            ->whereNotNull('due_date');

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        if ($request->filled('start') && $request->filled('end')) {
            $query->whereBetween('due_date', [
                Carbon::parse($request->start)->toDateString(),
                Carbon::parse($request->end)->toDateString(),
            ]);
        }

        $tasks = $query->get();
        $today = Carbon::today();

        $events = $tasks->map(function (Task $task) use ($today) {
            $isOverdue = $task->status !== 'done' && $task->due_date->lt($today);

            // Determine event color
            if ($task->status === 'done') {
                $bgColor = '#16A34A'; // success green
            } elseif ($isOverdue) {
                $bgColor = '#DC2626'; // danger red
            } elseif ($task->status === 'in_progress') {
                $bgColor = '#D97706'; // warning amber
            } else {
                $bgColor = '#2F855A'; // primary green
            }

            return [
                'id' => $task->id,
                'title' => $task->title,
                'start' => $task->due_date->format('Y-m-d'),
                'url' => route('tasks.show', $task),
                'backgroundColor' => $bgColor,
                'borderColor' => $bgColor,
                'textColor' => '#FFFFFF',
                'extendedProps' => [
                    'status' => $task->status,
                    'priority' => $task->priority,
                    'project' => $task->project ? $task->project->name : 'Pribadi',
                    'is_overdue' => $isOverdue,
                ],
            ];
        });

        return response()->json($events);
    }
}
