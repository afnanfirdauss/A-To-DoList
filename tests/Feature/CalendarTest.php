<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CalendarTest extends TestCase
{
    use RefreshDatabase;

    public function test_calendar_page_and_events_endpoint_return_correct_data(): void
    {
        $user = User::factory()->create();

        $task = Task::create([
            'created_by' => $user->id,
            'title' => 'Deadline Penting',
            'status' => 'in_progress',
            'priority' => 'high',
            'due_date' => Carbon::tomorrow(),
        ]);

        $responsePage = $this->actingAs($user)->get(route('calendar.index'));
        $responsePage->assertStatus(200);
        $responsePage->assertSee('Kalender Deadline');

        $responseEvents = $this->actingAs($user)->getJson(route('calendar.events'));
        $responseEvents->assertStatus(200);
        $responseEvents->assertJsonFragment([
            'id' => $task->id,
            'title' => 'Deadline Penting',
            'start' => Carbon::tomorrow()->toDateString(),
        ]);
    }
}
