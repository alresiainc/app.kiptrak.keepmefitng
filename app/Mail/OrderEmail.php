<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;
    public $content;

    /**
     * Create a new message instance.
     *
     * @param string $subject
     * @param string $content // Renamed parameter to $content
     */
    public function __construct($subject, $content) // Adjusted constructor parameter
    {
        $this->subject = $subject;
        $this->content = $content; // Set the content
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.order-email')
            ->subject($this->subject)
            ->with(['content' => $this->content]); // Pass the new variable
    }
}
