<?php

namespace App\Notifications;

use App\Models\Agent;
use App\Models\Customer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Message;
use App\Models\User;

class SendNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $messages;
    protected $getTo;

    /**
     * Create a new notification instance.
     *
     * @param string|array $message
     */
    public function __construct($message, $to = null)
    {

        $this->getTo = $to ?? 'unknown';
        $this->messages = $this->processMessages($message);
    }

    /**
     * Process the messages to standardize their format.
     *
     * @param string|array $messages
     * @return array
     */
    protected function processMessages($messages)
    {
        if (is_string($messages)) {
            return [
                'email' => ['message' => $messages, 'title' => 'Plain Message'],
                'sms' => ['message' => $messages, 'title' => 'Plain Message'],
                'whatsapp' => ['message' => $messages, 'title' => 'Plain Message'],
            ];
        }

        if (is_array($messages)) {
            $formattedMessages = [];
            foreach ($messages as $channel => $message) {
                $formattedMessages[$channel] = [
                    'title' => $message['title'] ?? 'Plain Message',
                    'message' => $message['message'] ?? $message,
                ];
            }
            return $formattedMessages;
        }

        return []; // Fallback if the message format is invalid
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [
            \App\Channels\EmailChannel::class,
            \App\Channels\WhatsAppChannel::class,
            \App\Channels\SmsChannel::class,
        ];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toEmail($notifiable)
    {


        if (!isset($this->messages['email'])) {
            return [];
        }

        $title = $this->messages['email']['title'];
        $message = $this->messages['email']['message'];

        // Check if $notifiable is an instance of AnonymousNotifiable
        if ($notifiable instanceof \Illuminate\Notifications\AnonymousNotifiable) {
            // Extract the first route for your custom channel
            $recipients = $notifiable->routes[\App\Channels\EmailChannel::class] ?? [];
        } else {
            // Handle other types of notifiable objects, e.g., User models
            $recipients = [$notifiable->routeNotificationForEmail()] ?? [];
        }


        return Message::create([
            'topic' => $title,
            'to' => $this->setTo($notifiable),
            'message' => $message,
            'type' => 'email',
            'recipients' => \serialize($recipients),
            'message_status' => 'pending',
        ]);
    }

    public function setTo($notifiable)
    {
        return match (true) {
            $notifiable instanceof Agent => 'agents',
            $notifiable instanceof Customer => 'customers',
            $notifiable instanceof User => 'employees',
            default => $this->getTo
        };
    }

    /**
     * Send a WhatsApp message.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toWhatsApp($notifiable)
    {
        if (!isset($this->messages['whatsapp'])) {
            return [];
        }

        $title = $this->messages['whatsapp']['title'];
        $message = $this->messages['whatsapp']['message'];

        // Check if $notifiable is an instance of AnonymousNotifiable
        if ($notifiable instanceof \Illuminate\Notifications\AnonymousNotifiable) {
            // Extract the first route for your custom channel
            $recipients = $notifiable->routes[\App\Channels\WhatsAppChannel::class] ?? [];
        } else {
            // Handle other types of notifiable objects, e.g., User models
            $recipients = [$notifiable->routeNotificationForWhatsapp()] ?? [];
        }

        return Message::create([
            'topic' => $title,
            'to' => $this->setTo($notifiable),
            'message' => $message,
            'type' => 'whatsapp',
            'recipients' => \serialize($recipients),
            'message_status' => 'pending',
        ]);
    }

    /**
     * Send an SMS message.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toSms($notifiable)
    {
        if (!isset($this->messages['sms'])) {
            return [];
        }

        $title = $this->messages['sms']['title'];
        $message = $this->messages['sms']['message'];

        // Check if $notifiable is an instance of AnonymousNotifiable
        if ($notifiable instanceof \Illuminate\Notifications\AnonymousNotifiable) {
            // Extract the first route for your custom channel
            $recipients = $notifiable->routes[\App\Channels\SmsChannel::class] ?? [];
        } else {
            // Handle other types of notifiable objects, e.g., User models
            $recipients = [$notifiable->routeNotificationForSMS()] ?? [];
        }


        return Message::create([
            'topic' => $title,
            'to' => $this->setTo($notifiable),
            'message' => $message,
            'type' => 'sms',
            'recipients' => \serialize($recipients),
            'message_status' => 'pending',
        ]);
    }
}
