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

    /**
     * Create a new event instance.
     */
    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel
     */
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
