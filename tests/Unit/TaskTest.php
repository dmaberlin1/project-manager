<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Task;

class TaskTest extends TestCase
{
    public function test_task_has_project_relationship()
    {
        $task = Task::factory()->create();

        $this->assertNotNull($task->project);
    }

    public function test_task_can_be_completed()
    {
        $task = Task::factory()->create(['status' => 'pending']);

        $task->update(['status' => 'completed']);

        $this->assertEquals('completed', $task->status);
    }
}
