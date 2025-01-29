<?php

namespace App\Services\Interfaces;

interface MailInterface
{
    public function send(array $emailData): void;
}
