<?php

namespace Tests\Feature;

use App\Models\Notification;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationAndCommentTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_add_comment_and_trigger_notification(): void
    {
        $creator = User::factory()->create();
        $assignee = User::factory()->create();

        $task = Task::create([
            'created_by' => $creator->id,
            'assigned_to' => $assignee->id,
            'title' => 'Tugas Kolaborasi',
            'status' => 'in_progress',
            'priority' => 'medium',
        ]);

        // Assignee adds comment
        $response = $this->actingAs($assignee)->post(route('comments.store', $task), [
            'body' => 'Saya sudah mulai mengerjakan modul ini.',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('comments', [
            'task_id' => $task->id,
            'user_id' => $assignee->id,
            'body' => 'Saya sudah mulai mengerjakan modul ini.',
        ]);

        // Notification should be sent to creator
        $this->assertDatabaseHas('notifications', [
            'user_id' => $creator->id,
            'task_id' => $task->id,
            'type' => 'task_comment',
        ]);
    }

    public function test_check_deadlines_command_sends_notifications_with_deduplication(): void
    {
        $user = User::factory()->create();

        // Overdue task
        $task = Task::create([
            'created_by' => $user->id,
            'assigned_to' => $user->id,
            'title' => 'Tugas Melewati Deadline',
            'status' => 'todo',
            'priority' => 'high',
            'due_date' => Carbon::yesterday(),
        ]);

        // Run artisan command first time
        $this->artisan('tasks:check-deadlines')
            ->expectsOutputToContain('new deadline notifications sent.')
            ->assertExitCode(0);

        $this->assertDatabaseHas('notifications', [
            'user_id' => $user->id,
            'task_id' => $task->id,
            'type' => 'overdue',
        ]);

        $countBefore = Notification::where('task_id', $task->id)->where('type', 'overdue')->count();

        // Run again on the same day -> deduplication should prevent duplicate notification
        $this->artisan('tasks:check-deadlines')
            ->assertExitCode(0);

        $countAfter = Notification::where('task_id', $task->id)->where('type', 'overdue')->count();
        $this->assertEquals($countBefore, $countAfter);
    }
}
