<?php

namespace App\Http\Controllers;

use App\Jobs\SendEmailJob;
use App\Models\User;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function sendNotifications(Request $request)
    {
        $users = User::all();

        foreach ($users as $user) {
            $emailData = [
                'email' => $user->email,
                'subject' => 'Новое уведомление',
                'title' => 'Привет, ' . $user->name,
                'message' => 'У вас новое уведомление в системе.',
            ];

            SendEmailJob::dispatch($emailData)->onQueue('emails');
        }

        return response()->json(['message' => 'Уведомления отправлены в очередь']);
    }
}
