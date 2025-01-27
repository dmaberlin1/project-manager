<?php

namespace App\Jobs;

use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DeleteProject implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $project;

    /**
     * Create a new job instance.
     */
    public function __construct(Project $project)
    {
        $this->project=$project;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->project->delete();
    }
}
