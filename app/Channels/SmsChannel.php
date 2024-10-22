<?php

namespace App\Channels;

use App\Services\NotificationService;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class SmsChannel
{

    protected $notificationService;

    // Use Dependency Injection for NotificationService
    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }


    public function send($notifiable, Notification $notification)
    {

        if (!method_exists($notification, 'toSms')) {
            throw new RuntimeException('Notification is missing toSms.');
        }
        $smsData = $notification->toSms($notifiable);
        if (empty($smsData)) {
            Log::info('No sms to send.');
            return; // Return early to avoid unnecessary processing
        }

        $messageData = $notification->toSms($notifiable);
        // Use your NotificationService to send the message
        $this->notificationService->sendSMSMessage($messageData);
    }
}
