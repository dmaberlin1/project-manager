<?php

namespace App\Http\Controllers;

use App\Http\Requests\SendNotificationRequest;
use App\Jobs\SendEmailJob;
use App\Models\User;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function sendNotifications(SendNotificationRequest $request)
    {
        $emailData = [
            'subject' => $request->subject,
            'title' => $request->title,
            'message' => $request->message,
        ];


        foreach (User::all() as $user) {
            $emailData['email'] = $user->email;
            SendEmailJob::dispatch($emailData)->onQueue('emails');
        }

        return response()->json(['message' => 'Уведомления отправлены в очередь']);
    }
}
