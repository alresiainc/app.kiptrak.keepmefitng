<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Log;

class WhatsAppChannel
{
    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        // Retrieve the WhatsApp message and recipient from the notification
        $messageData = $notification->toWhatsapp($notifiable);

        if (!$messageData) {
            // Log missing message data
            Log::warning('No message data returned from the notification.');
            return;
        }

        // Ensure that 'message' and 'to' are set in messageData
        if (empty($messageData['message']) || empty($messageData['to'])) {
            Log::warning('Missing required fields in message data.', ['data' => $messageData]);
            return;
        }

        // Normalize 'to' to handle both single and multiple phone numbers
        $phones = is_array($messageData['to']) ? $messageData['to'] : [$messageData['to']];
        $dataToSend = [];

        foreach ($phones as $phone) {
            // Filter out any invalid phone numbers (empty or improperly formatted)
            if (empty($phone)) {
                Log::warning('Invalid phone number encountered.', ['phone' => $phone]);
                continue;
            }

            // Create a new message data object for each phone number
            $individualMessageData = [
                'number' => $phone,
                'message' => $messageData['message'],
                'session_name' => $messageData['session_name'],
                // Include any other fields from messageData if necessary
            ];

            $dataToSend[] = $individualMessageData;
        }

        // If no valid data entries exist after filtering, log it and return
        if (empty($dataToSend)) {
            Log::warning('No valid message entries to send after filtering.', ['messageData' => $messageData]);
            return;
        }

        // Use your NotificationService to send the message
        $notificationService = new NotificationService();
        $notificationService->sendWhatsAppMessage($dataToSend);
    }
}
