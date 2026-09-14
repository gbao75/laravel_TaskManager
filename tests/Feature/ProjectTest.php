<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_project(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->post(route('projects.store'), [
                'name' => 'Laravel Project',
                'description' => 'Test project',
            ]);

        $response->assertRedirect(route('projects.index'));

        $this->assertDatabaseHas('projects', [
            'user_id' => $user->id,
            'name' => 'Laravel Project',
            'description' => 'Test project',
        ]);
    }

    public function test_project_name_is_required(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->post(route('projects.store'), [
                'name' => '',
                'description' => 'Test project',
            ]);

        $response->assertSessionHasErrors('name');

        $this->assertDatabaseCount('projects', 0);
    }

    public function test_user_cannot_edit_another_users_project(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();

        $project = Project::create([
            'user_id' => $owner->id,
            'name' => 'Owner Project',
            'description' => null,
        ]);

        $response = $this
            ->actingAs($otherUser)
            ->get(route('projects.edit', $project));

        $response->assertForbidden();
    }

    public function test_user_cannot_delete_another_users_project(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();

        $project = Project::create([
            'user_id' => $owner->id,
            'name' => 'Owner Project',
            'description' => null,
        ]);

        $response = $this
            ->actingAs($otherUser)
            ->delete(route('projects.destroy', $project));

        $response->assertForbidden();

        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
        ]);
    }
}