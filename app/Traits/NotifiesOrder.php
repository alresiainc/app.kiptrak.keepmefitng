<?php

namespace App\Traits;

use App\Services\NotificationService;

trait NotifiesOrder
{
    /**
     * Send a WhatsApp message to the assigned staff and the customer.
     */
    public function sendWhatsappMessage()
    {
        $notificationService = new NotificationService();

        // Send WhatsApp message to the assigned staff if present
        if ($this->staffAssigned) {
            $staffPhone = $this->staffAssigned->phone;
            $staffMessage = $this->generateStaffMessage();
            $notificationService->sendWhatsapp($staffPhone, $staffMessage);
        }

        // Send WhatsApp message to the customer if present
        if ($this->customer) {
            $customerPhone = $this->customer->phone;
            $customerMessage = $this->generateCustomerMessage();
            $notificationService->sendWhatsapp($customerPhone, $customerMessage);
        }
    }

    /**
     * Send an email to the assigned staff and the customer.
     */
    public function sendEmail()
    {
        $notificationService = new NotificationService();

        // Send email to the assigned staff if present
        if ($this->staffAssigned) {
            $staffEmail = $this->staffAssigned->email;
            $staffEmailContent = $this->generateStaffEmailContent();
            $notificationService->sendEmail($staffEmail, 'Order Assigned Notification', $staffEmailContent);
        }

        // Send email to the customer if present
        if ($this->customer) {
            $customerEmail = $this->customer->email;
            $customerEmailContent = $this->generateCustomerEmailContent();
            $notificationService->sendEmail($customerEmail, 'Order Update', $customerEmailContent);
        }
    }

    /**
     * Generate the WhatsApp message content for the staff.
     */
    protected function generateStaffMessage()
    {
        return "Hello {$this->staffAssigned->name}, you have been assigned a new order with ID {$this->id}. Please review and take action.";
    }

    /**
     * Generate the WhatsApp message content for the customer.
     */
    protected function generateCustomerMessage()
    {
        return "Dear {$this->customer->name}, your order with ID {$this->id} has been received and is being processed. We will keep you updated!";
    }

    /**
     * Generate the email content for the staff.
     */
    protected function generateStaffEmailContent()
    {
        return "<p>Dear {$this->staffAssigned->name},</p>
                <p>You have been assigned a new order with ID {$this->id}. Please review the order and take necessary action.</p>
                <p>Thank you!</p>";
    }

    /**
     * Generate the email content for the customer.
     */
    protected function generateCustomerEmailContent()
    {
        return "<p>Dear {$this->customer->name},</p>
                <p>We have received your order with ID {$this->id} and it is currently being processed. We will notify you when it's completed.</p>
                <p>Thank you for your trust in our service!</p>";
    }
}
