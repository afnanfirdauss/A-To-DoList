<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_project(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('projects.store'), [
            'name' => 'Proyek Alpha',
            'description' => 'Deskripsi proyek alpha',
            'color' => '#2F855A',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('projects', [
            'name' => 'Proyek Alpha',
            'user_id' => $user->id,
        ]);
    }

    public function test_owner_can_add_and_remove_member(): void
    {
        $owner = User::factory()->create();
        $member = User::factory()->create(['email' => 'colleague@test.com']);

        $project = Project::create([
            'user_id' => $owner->id,
            'name' => 'Proyek Kolaborasi',
        ]);

        // Add member
        $responseAdd = $this->actingAs($owner)->post(route('projects.members.add', $project), [
            'email' => 'colleague@test.com',
        ]);

        $responseAdd->assertSessionHas('success');
        $this->assertTrue($project->fresh()->members->contains($member->id));

        // Member can now access project
        $responseView = $this->actingAs($member)->get(route('projects.show', $project));
        $responseView->assertStatus(200);

        // Remove member
        $responseRemove = $this->actingAs($owner)->delete(route('projects.members.remove', [$project, $member]));
        $responseRemove->assertSessionHas('success');
        $this->assertFalse($project->fresh()->members->contains($member->id));
    }

    public function test_non_member_cannot_access_project(): void
    {
        $owner = User::factory()->create();
        $outsider = User::factory()->create();

        $project = Project::create([
            'user_id' => $owner->id,
            'name' => 'Proyek Rahasia',
        ]);

        $response = $this->actingAs($outsider)->get(route('projects.show', $project));
        $response->assertStatus(403);
    }
}
