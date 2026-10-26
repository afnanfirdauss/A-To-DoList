<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Services\DashboardService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the productivity dashboard with real-time analytics.
     */
    public function index(Request $request, DashboardService $dashboardService): View
    {
        $user = $request->user();
        $projectId = $request->filled('project_id') ? (int) $request->project_id : null;

        $projects = Project::where('user_id', $user->id)
            ->orWhereHas('members', fn ($q) => $q->where('users.id', $user->id))
            ->orderBy('name')
            ->get();

        $data = $dashboardService->getDashboardData($user, $projectId);

        return view('dashboard.index', array_merge($data, [
            'projects' => $projects,
            'selectedProjectId' => $projectId,
        ]));
    }
}
