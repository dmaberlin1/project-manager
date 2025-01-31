<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class NotificationEmail extends Mailable
{
    public $emailData;

    public function __construct(array $emailData)
    {
        $this->emailData = $emailData;
    }

    public function build()
    {
        return $this->to($this->emailData['email'])
            ->subject($this->emailData['subject'])
            ->view('emails.notification')
            ->with(['data' => $this->emailData]);
    }
}
