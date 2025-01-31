<?php

namespace Feature\Controllers;

use App\Jobs\DeleteProjectJob;
use App\Models\Project;
use Illuminate\Support\Facades\Queue;
use Tests\Feature\BaseTestCase;

class ProjectControllerTest extends BaseTestCase
{
    /** @test */
    public function it_displays_project_list(): void
    {
        Project::factory()->create(['user_id' => $this->user->id]);

        $this->actingAs($this->user)
            ->get(route('projects.index'))
            ->assertOk()
            ->assertViewIs('projects.index')
            ->assertViewHas('projects');
    }

    /** @test */
    public function it_shows_create_project_page()
    {
        $this->actingAs($this->user)
            ->get(route('projects.create'))
            ->assertOk()
            ->assertViewIs('projects.create');
    }

    /** @test */
    public function it_stores_new_project()
    {
        $data = Project::factory()->make()->toArray();

        $this->actingAs($this->user)
            ->post(route('projects.store'), $data)
            ->assertRedirect(route('projects.index'))
            ->assertSessionHas('success', 'Проект успешно создан.');

        $this->assertDatabaseHas('projects', ['name' => $data['name']]);
    }

    /** @test */
    public function it_shows_project_details()
    {
        $project = Project::factory()->create(['user_id' => $this->user->id]);

        $this->actingAs($this->user)
            ->get(route('projects.show', $project))
            ->assertOk()
            ->assertViewIs('projects.show')
            ->assertViewHas('project', $project);
    }

    /** @test */
    public function it_shows_edit_page_for_project()
    {
        $project = Project::factory()->create(['user_id' => $this->user->id]);

        $this->actingAs($this->user)
            ->get(route('projects.edit', $project))
            ->assertOk()
            ->assertViewIs('projects.edit')
            ->assertViewHas('project', $project);
    }

    /** @test */
    public function it_updates_project()
    {
        $project = Project::factory()->create(['user_id' => $this->user->id]);
        $newData = ['name' => 'Обновленное название'];

        $this->actingAs($this->user)
            ->put(route('projects.update', $project), $newData)
            ->assertRedirect(route('projects.index'))
            ->assertSessionHas('success', 'Проект успешно обновлен.');

        $this->assertDatabaseHas('projects', ['id' => $project->id, 'name' => 'Обновленное название']);
    }

    /** @test */
    public function it_deletes_project()
    {
        $project = Project::factory()->create(['user_id' => $this->user->id]);

        $this->actingAs($this->user)
            ->delete(route('projects.destroy', $project))
            ->assertRedirect(route('projects.index'))
            ->assertSessionHas('success', 'Проект успешно удален.');

        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
    }

    /** @test */
    public function it_schedules_project_deletion()
    {
        Queue::fake();
        $project = Project::factory()->create(['user_id' => $this->user->id]);

        $this->actingAs($this->user)
            ->delete(route('projects.deleteDelayed', $project->id))
            ->assertJson(['message' => 'Проект будет удален через 10 минут']);

        Queue::assertPushed(DeleteProjectJob::class, fn($job) => $job->projectId === $project->id);
    }
}
