<?php

namespace App\Events;

use App\Models\Task;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Queue\SerializesModels;

class TaskCreated
{
    use InteractsWithSockets, SerializesModels;

    public $task;


    public function __construct(Task $task)
    {
        $this->task = $task;
    }


    public function broadcastOn(): Channel
    {
        return new PrivateChannel('projects. ' . $this->task->project_id);
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->task->id,
            'title' => $this->task->title,
            'assignee' => $this->task->assignee->name,
        ];
    }
}
