<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;
use App\Models\CustomNotification;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class DatabaseChannel
{
    public function send($notifiable, Notification $notification)
    {

        if (!method_exists($notification, 'toDatabase')) {
            Log::error('Notification is missing toDatabase.');
        }

        $databaseData = $notification->toDatabase($notifiable);
        if (empty($databaseData)) {
            Log::info('No database to send.');
            return; // Return early to avoid unnecessary processing
        } else {

            $messageData = $notification->toDatabase($notifiable);
        }
    }
}
