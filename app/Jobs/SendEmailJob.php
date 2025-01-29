<?php

namespace App\Jobs;

use App\Services\Interfaces\MailInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $emailData;
    protected MailInterface $mailService;

    /**
     * Create a new job instance.
     */
    public function __construct(array $emailData, MailInterface $mailService)
    {
        $this->emailData = $emailData;
        $this->mailService = $mailService;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->mailService->send($this->emailData);
    }
}
