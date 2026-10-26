<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_tasks_list(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('tasks.index'));

        $response->assertStatus(200);
        $response->assertSee('Daftar Tugas');
    }

    public function test_user_can_create_a_valid_task(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('tasks.store'), [
            'title' => 'Tugas Baru Belajar Laravel',
            'description' => 'Mempelajari controller, request, dan policy',
            'status' => 'todo',
            'priority' => 'high',
            'start_date' => now()->toDateString(),
            'due_date' => now()->addDays(2)->toDateString(),
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('tasks', [
            'title' => 'Tugas Baru Belajar Laravel',
            'created_by' => $user->id,
            'status' => 'todo',
            'priority' => 'high',
        ]);
    }

    public function test_task_creation_fails_when_start_date_exceeds_due_date(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('tasks.store'), [
            'title' => 'Tugas Tanggal Salah',
            'status' => 'todo',
            'priority' => 'medium',
            'start_date' => '2026-10-30',
            'due_date' => '2026-10-25', // earlier than start_date
        ]);

        $response->assertSessionHasErrors('due_date');
    }

    public function test_task_creation_fails_with_invalid_status_or_priority(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('tasks.store'), [
            'title' => 'Invalid Enum Task',
            'status' => 'invalid_status',
            'priority' => 'extreme',
        ]);

        $response->assertSessionHasErrors(['status', 'priority']);
    }

    public function test_user_cannot_view_or_edit_another_users_private_task(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        $taskA = Task::create([
            'created_by' => $userA->id,
            'title' => 'Rahasia User A',
            'status' => 'todo',
            'priority' => 'low',
        ]);

        // User B tries to view
        $responseView = $this->actingAs($userB)->get(route('tasks.show', $taskA));
        $responseView->assertStatus(403);

        // User B tries to edit
        $responseEdit = $this->actingAs($userB)->get(route('tasks.edit', $taskA));
        $responseEdit->assertStatus(403);

        // User B tries to delete
        $responseDelete = $this->actingAs($userB)->delete(route('tasks.destroy', $taskA));
        $responseDelete->assertStatus(403);
    }

    public function test_kanban_status_update_via_ajax_updates_completed_at(): void
    {
        $user = User::factory()->create();

        $task = Task::create([
            'created_by' => $user->id,
            'title' => 'Tugas Menuju Done',
            'status' => 'in_progress',
            'priority' => 'medium',
        ]);

        $this->assertNull($task->completed_at);

        // Move to Done
        $response = $this->actingAs($user)->patchJson(route('tasks.update-status', $task), [
            'status' => 'done',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);

        $task->refresh();
        $this->assertEquals('done', $task->status);
        $this->assertNotNull($task->completed_at);

        // Reopen to in_progress
        $reopenResponse = $this->actingAs($user)->patchJson(route('tasks.update-status', $task), [
            'status' => 'in_progress',
        ]);

        $reopenResponse->assertStatus(200);
        $task->refresh();
        $this->assertEquals('in_progress', $task->status);
        $this->assertNull($task->completed_at);
    }
}
