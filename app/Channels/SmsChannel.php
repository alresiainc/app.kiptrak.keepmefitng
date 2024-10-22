<?php

namespace App\Channels;

use App\Helpers\PhoneHelper;
use App\Models\Message;
use App\Services\NotificationService;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class SmsChannel
{
    protected $notificationService;

    /**
     * SmsChannel constructor.
     * 
     * @param NotificationService $notificationService
     */
    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Send SMS notification.
     * 
     * @param mixed $notifiable
     * @param Notification $notification
     * 
     * @throws RuntimeException
     */
    public function send($notifiable, Notification $notification)
    {
        // Ensure the notification has the toSms method
        if (!method_exists($notification, 'toSms')) {
            throw new RuntimeException('Notification is missing toSms method.');
        }

        // Fetch the SMS data
        $smsData = $notification->toSms($notifiable);
        if (empty($smsData)) {
            Log::info('No SMS data to send.');
            return; // Return early if no SMS data is present
        }

        // Deserialize the recipients list
        $recipients = unserialize($smsData['recipients']);
        $phones = is_array($recipients) ? $recipients : [$recipients]; // Normalize 'recipients' to array

        // Format each phone number to the international format
        $contacts = array_map(function ($phone) {
            return PhoneHelper::formatToInternational($phone, '+234'); // Nigeria's country code
        }, $phones);

        $message = $smsData['message'];

        // Prepare the data to send
        $dataToSend = [
            'contacts' => $contacts,
            'message' => $message,
        ];

        // Use NotificationService to send the SMS message
        $this->notificationService->sendSMSMessage($dataToSend);

        // Update the message status to 'sent'
        Message::where('id', $smsData['id'])->update(['status' => 'sent', 'message_status' => 'sent']);
    }
}
