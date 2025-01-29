<?php

namespace App\Services;

interface MailInterface
{
    public function send(array $emailData): void;
}
