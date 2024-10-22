<?php

namespace App\Channels;

use App\Helpers\PhoneHelper;
use App\Models\Message;
use App\Models\User;
use Illuminate\Notifications\Notification;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Log;

class WhatsAppChannel
{
    protected $notificationService;

    // Use Dependency Injection for NotificationService
    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        // Check if the notification has the toWhatsApp method
        if (!method_exists($notification, 'toWhatsApp')) {
            Log::error('Notification is missing toWhatsApp.');
            return; // Return early to avoid unnecessary processing
        }

        // Retrieve the WhatsApp message and recipient from the notification
        $whatsappData = $notification->toWhatsApp($notifiable);
        if (empty($whatsappData)) {
            Log::info('No WhatsApp message to send.');
            return;
        }



        // Ensure that 'message' and 'to' are set in whatsappData
        if (empty($whatsappData['message']) || empty($whatsappData['recipients'])) {
            Log::warning('Missing required fields in message data.', ['data' => $whatsappData]);
            return;
        }

        $recipients = unserialize($whatsappData['recipients']);

        // Normalize 'to' to handle both single and multiple phone numbers
        $phones = is_array($recipients) ? $recipients : [$recipients];
        $dataToSend = [];

        $staff = User::find($whatsappData['created_by']);

        foreach ($phones as $phone) {
            // Filter out any invalid phone numbers (empty or improperly formatted)
            if (empty($phone)) {
                Log::warning('Invalid phone number encountered.', ['phone' => $phone]);
                continue;
            }

            // Format the phone number
            $formattedPhoneNumber = PhoneHelper::formatToInternational($phone, '+234'); // Nigeria's country code
            $message = $whatsappData['message'] ?? '';
            $title = $whatsappData['topic'] ?? 'Order Notification';
            $body = '*' . $title . '*' . "\n" . $message;
            // Create a new message data object for each phone number
            $individualWhatsappData = [
                'number' => $formattedPhoneNumber,
                'message' => $body,
            ];

            if ($staff) {
                $session_name = $staff->adkombo_whatsapp_session_name ?? config('site.default_adkombo_whatsapp_session_name', '');
                if ($session_name) {
                    $individualWhatsappData['session_name'] = $session_name;
                }
            }

            $dataToSend[] = $individualWhatsappData;
        }

        // If no valid data entries exist after filtering, log it and return
        if (empty($dataToSend)) {
            Log::warning('No valid message entries to send after filtering.', ['whatsappData' => $whatsappData]);
            return;
        }



        // Use your NotificationService to send the message
        $this->notificationService->sendWhatsAppMessage($dataToSend);

        Message::where('id', $whatsappData['id'])->update(['status' => 'sent', 'message_status' => 'sent']);
    }
}
