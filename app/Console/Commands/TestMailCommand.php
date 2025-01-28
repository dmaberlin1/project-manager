<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestMailCommand extends Command
{
    protected $signature = 'mail:test';

    protected $description = 'Send a test email';

    public function handle()
    {
        Mail::raw('This is a test email from Laravel.', static function ($message) {
            $message->to('dmaberlin77@gmai.com')
                ->subject('Test Email');
        });

        $this->info('Test email sent!');
    }
}
