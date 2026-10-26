<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use App\Services\DashboardService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_displays_correct_metrics(): void
    {
        $user = User::factory()->create();

        // 1 completed task
        Task::create([
            'created_by' => $user->id,
            'title' => 'Tugas Selesai',
            'status' => 'done',
            'priority' => 'low',
            'completed_at' => now(),
        ]);

        // 1 in_progress overdue task
        Task::create([
            'created_by' => $user->id,
            'title' => 'Tugas Telat',
            'status' => 'in_progress',
            'priority' => 'high',
            'due_date' => Carbon::yesterday(),
        ]);

        // 1 todo task
        Task::create([
            'created_by' => $user->id,
            'title' => 'Tugas ToDo',
            'status' => 'todo',
            'priority' => 'medium',
            'due_date' => Carbon::tomorrow(),
        ]);

        $service = new DashboardService();
        $data = $service->getDashboardData($user);

        $this->assertEquals(3, $data['totalTasks']);
        $this->assertEquals(1, $data['completedTasks']);
        $this->assertEquals(1, $data['inProgressTasks']);
        $this->assertEquals(1, $data['todoTasks']);
        $this->assertEquals(1, $data['overdueTasks']);
        $this->assertEquals(1, $data['dueSoonTasks']);
        $this->assertEquals(33, $data['completionRate']); // 1/3 = 33%

        $response = $this->actingAs($user)->get(route('dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Tugas Selesai');
        $response->assertSee('33%');
    }

    public function test_completion_rate_is_zero_when_user_has_no_tasks(): void
    {
        $user = User::factory()->create();
        $service = new DashboardService();
        $data = $service->getDashboardData($user);

        $this->assertEquals(0, $data['totalTasks']);
        $this->assertEquals(0, $data['completionRate']);
    }
}
