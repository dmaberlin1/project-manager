<?php

namespace Tests\Feature\Controllers;

use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndexReturnsProjects()
    {
        Project::factory()->count(3)->create();

        $response = $this->get(route('projects.index'));

        $response->assertStatus(200);
        $response->assertViewHas('projects');
    }

    public function testStoreCreatesNewProject()
    {
        $projectData = [
            'name' => 'Test Project',
            'description' => 'Description for project',
        ];

        $response = $this->post(route('projects.store'), $projectData);

        $response->assertRedirect(route('projects.index'));
        $this->assertDatabaseHas('projects', ['name' => 'Test Project']);
    }
}
