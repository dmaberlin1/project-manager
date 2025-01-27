<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendBulkEmails implements ShouldQueue
{

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $emails;
    protected $message;

    /**
     * Create a new job instance.
     */
    public function __construct(array $emails, string $message)
    {
        $this->emails = $emails;
        $this->message = $message;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        foreach ($this->emails as $email) {
            Mail::raw($this->message, static function ($mail) use ($email) {
                $mail->to($email)->subject('Уведомление от системы управления задачами');
            });
        }
    }
}
