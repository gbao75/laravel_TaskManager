<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_task(): void
    {
        $user = User::factory()->create();

        $project = Project::create([
            'user_id' => $user->id,
            'name' => 'Laravel Project',
            'description' => null,
        ]);

        $response = $this
            ->actingAs($user)
            ->post(route('tasks.store'), [
                'project_id' => $project->id,
                'title' => 'Learn Laravel Testing',
                'description' => 'Feature test',
                'status' => 'pending',
                'deadline' => '2026-09-30',
            ]);

        $response->assertRedirect(route('tasks.index'));

        $this->assertDatabaseHas('tasks', [
            'user_id' => $user->id,
            'project_id' => $project->id,
            'title' => 'Learn Laravel Testing',
            'status' => 'pending',
        ]);
    }

    public function test_task_title_is_required(): void
    {
        $user = User::factory()->create();

        $project = Project::create([
            'user_id' => $user->id,
            'name' => 'Laravel Project',
            'description' => null,
        ]);

        $response = $this
            ->actingAs($user)
            ->post(route('tasks.store'), [
                'project_id' => $project->id,
                'title' => '',
                'status' => 'pending',
            ]);

        $response->assertSessionHasErrors('title');

        $this->assertDatabaseCount('tasks', 0);
    }

    public function test_user_cannot_create_task_for_another_users_project(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();

        $project = Project::create([
            'user_id' => $owner->id,
            'name' => 'Private Project',
            'description' => null,
        ]);

        $response = $this
            ->actingAs($otherUser)
            ->post(route('tasks.store'), [
                'project_id' => $project->id,
                'title' => 'Unauthorized Task',
                'status' => 'pending',
            ]);

        $response->assertNotFound();

        $this->assertDatabaseMissing('tasks', [
            'title' => 'Unauthorized Task',
        ]);
    }

    public function test_user_cannot_edit_another_users_task(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();

        $project = Project::create([
            'user_id' => $owner->id,
            'name' => 'Owner Project',
            'description' => null,
        ]);

        $task = Task::create([
            'project_id' => $project->id,
            'user_id' => $owner->id,
            'title' => 'Private Task',
            'description' => null,
            'status' => 'pending',
            'deadline' => null,
        ]);

        $response = $this
            ->actingAs($otherUser)
            ->get(route('tasks.edit', $task));

        $response->assertForbidden();
    }

    public function test_user_can_update_task_status_via_ajax(): void
    {
        $user = User::factory()->create();

        $project = Project::create([
            'user_id' => $user->id,
            'name' => 'Laravel Project',
            'description' => null,
        ]);

        $task = Task::create([
            'project_id' => $project->id,
            'user_id' => $user->id,
            'title' => 'AJAX Task',
            'description' => null,
            'status' => 'pending',
            'deadline' => null,
        ]);

        $response = $this
            ->actingAs($user)
            ->patchJson(
                route('tasks.updateStatus', $task),
                [
                    'status' => 'completed',
                ]
            );

        $response
            ->assertOk()
            ->assertJson([
                'success' => true,
                'status' => 'completed',
            ]);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'status' => 'completed',
        ]);
    }

    public function test_user_cannot_update_another_users_task_status(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();

        $project = Project::create([
            'user_id' => $owner->id,
            'name' => 'Owner Project',
            'description' => null,
        ]);

        $task = Task::create([
            'project_id' => $project->id,
            'user_id' => $owner->id,
            'title' => 'Private Task',
            'description' => null,
            'status' => 'pending',
            'deadline' => null,
        ]);

        $response = $this
            ->actingAs($otherUser)
            ->patchJson(
                route('tasks.updateStatus', $task),
                [
                    'status' => 'completed',
                ]
            );

        $response->assertForbidden();

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'status' => 'pending',
        ]);
    }
}