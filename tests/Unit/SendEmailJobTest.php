<?php

namespace Tests\Unit;



use Tests\TestCase;
use App\Jobs\SendEmailJob;
use Illuminate\Support\Facades\Mail;

class SendEmailJobTest extends TestCase
{
    public function test_email_job_sends_email()
    {
        Mail::fake();

        $emailData = [
            'email' => 'user@example.com',
            'subject' => 'Test Email',
            'message' => 'This is a test email.',
        ];

        $job = new SendEmailJob($emailData);
        $job->handle();

        Mail::assertSent(function ($mail) use ($emailData) {
            return $mail->to[0]['address'] === $emailData['email'] &&
                $mail->subject === $emailData['subject'];
        });
    }
}
