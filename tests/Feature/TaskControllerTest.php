<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_task()
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'api')
            ->postJson('/api/tasks', [
                'name' => 'Test Task',
                'status' => 'pending',
                'project_id' => 1,
            ])
            ->assertStatus(201)
            ->assertJson([
                'name' => 'Test Task',
                'status' => 'pending',
            ]);
    }

    public function test_can_list_tasks()
    {
        $user = User::factory()->create();
        Task::factory()->count(5)->create();

        $this->actingAs($user, 'api')
            ->getJson('/api/tasks')
            ->assertStatus(200)
            ->assertJsonCount(5, 'data');
    }

    public function test_can_update_task()
    {
        $user = User::factory()->create();
        $task = Task::factory()->create();

        $this->actingAs($user, 'api')
            ->putJson("/api/tasks/{$task->id}", [
                'name' => 'Updated Task',
            ])
            ->assertStatus(200)
            ->assertJson([
                'name' => 'Updated Task',
            ]);
    }

    public function test_can_delete_task()
    {
        $user = User::factory()->create();
        $task = Task::factory()->create();

        $this->actingAs($user, 'api')
            ->deleteJson("/api/tasks/{$task->id}")
            ->assertStatus(200);
    }
}
