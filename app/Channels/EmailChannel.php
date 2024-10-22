<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Mail;
use RuntimeException;
use App\Mail\OrderEmail; // Import the Mailable class
use App\Models\Message;
use Illuminate\Support\Facades\Log;

class EmailChannel
{
    public function send($notifiable, Notification $notification)
    {
        // Check if the notification has the toEmail method
        if (!method_exists($notification, 'toEmail')) {
            Log::error('Notification is missing toEmail method.');
            return; // Return early
        }

        // Retrieve the email message data from the notification
        $messageData = $notification->toEmail($notifiable);

        // Check if the email data is empty
        if (empty($messageData)) {
            Log::info('No email data provided by notification.');
            return; // Return early
        }

        // Check if messageData is structured properly
        if (!isset($messageData['recipients']) || !isset($messageData['topic']) || !isset($messageData['message'])) {
            Log::error('The email message data is not properly structured.');
            return; // Return early
        }

        // Handle recipients
        // $recipients = $messageData['recipients'];
        $recipients = \unserialize($messageData['recipients']);
        $emails = is_array($recipients) ? $recipients : [$recipients]; // Normalize 'recipients'

        // Email details
        $subject = $messageData['topic'];
        $message = $messageData['message'];

        // Send email and log success/failure
        try {
            Mail::to($emails)->send(new OrderEmail($subject, $message));
            Message::where('id', $messageData['id'])->update(['status' => 'sent', 'message_status' => 'sent']);
            Log::info('Email sent successfully to: ' . implode(', ', $emails));
        } catch (\Exception $e) {
            Log::error('Failed to send email: ' . $e->getMessage());
        }
    }
}
