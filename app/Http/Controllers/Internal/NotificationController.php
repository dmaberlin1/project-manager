<?php

namespace App\Http\Controllers\Internal;

use App\Http\Controllers\Controller;
use App\Http\Requests\SendNotificationRequest;
use App\Jobs\SendEmailJob;
use App\Models\User;

class NotificationController extends Controller
{
    public function sendNotifications(SendNotificationRequest $request): \Illuminate\Http\JsonResponse
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
