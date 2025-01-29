<?php

namespace App\Services;

use App\Mail\NotificationEmail;
use App\Services\Interfaces\MailInterface;
use Illuminate\Support\Facades\Mail;

class MailService implements MailInterface
{

    public function send(array $emailData): void
    {
        Mail::send(new NotificationEmail($emailData));
    }
}
