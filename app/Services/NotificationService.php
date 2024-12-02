<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class NotificationService
{
    /**
     * The WhatsApp API URL.
     * 
     * @var string
     */
    protected string $whatsAppApiUrl;

    /**
     * The WhatsApp API key used for authentication.
     * 
     * @var string
     */
    protected string $whatsAppApiKey;

    /**
     * The SMS API URL.
     * 
     * @var string
     */
    protected string $smsApiUrl;

    /**
     * The SMS API key used for authentication.
     * 
     * @var string
     */
    protected string $smsApiKey;

    /**
     * The SMS API Sender name
     * 
     * @var string
     */
    protected string $smsSender;

    /**
     * Constructor to initialize API URLs and API keys.
     */
    public function __construct()
    {
        $this->whatsAppApiUrl = config('site.adkombo_whatsapp.api_url', 'https://ad.adkombo.com/api/whatsapp/send');
        $this->whatsAppApiKey = config('site.adkombo_whatsapp.api_key', 'e1961a42-abd3-4f32-80f8-54d24d86a6c5');

        $this->smsApiUrl = config('site.bulk_sms_nigeria.api_url', 'https://www.bulksmsnigeria.com/api/v1/sms/create');
        $this->smsApiKey = config('site.bulk_sms_nigeria.api_token', 'qEbZEBUsgTjDGsaVe09Cop1yLnrNrByMifqcP0U2TBzO27rBWOwX0Ssr35I5');
        $this->smsSender = config('site.bulk_sms_nigeria.sender_name', 'KIPTRAK');
    }

    /**
     * Send a WhatsApp message.
     * 
     * @param array $contacts
     * @return array
     */
    public function sendWhatsAppMessage(array $contacts): array
    {
        $postData = [
            'contact' => $contacts
        ];

        return $this->sendRequest($this->whatsAppApiUrl, $postData, [
            'Api-key' => $this->whatsAppApiKey,
            'Content-Type' => 'application/json'
        ]);
    }

    /**
     * Send an SMS message.
     * 
     * @param array $messageData
     * @return array
     */
    public function sendSMSMessage(array $messageData): array
    {
        $to = is_array($messageData['contacts']) ? implode(',', $messageData['contacts']) : $messageData['contacts'];
        $body = $messageData['message'];

        $postData = [
            'api_token' => $this->smsApiKey,
            'from' => $this->smsSender,
            'to' => $to,
            'body' => $body,
            // 'gateway' => 'direct-refund',
        ];

        return $this->sendRequest($this->smsApiUrl, $postData, [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json'
        ]);
    }

    /**
     * Send a HTTP POST request to the given API endpoint.
     * 
     * @param string $url
     * @param array $data
     * @param array $headers
     * @return array
     */
    protected function sendRequest(string $url, array $data, array $headers = []): array
    {
        try {
            Log::info('Sending request to: ' . $url, ['payload' => $data, 'headers' => $headers]);

            $response = Http::withHeaders($headers)->post($url, $data);
            Log::info('Request response', ['response' => $response]);
            if ($response->failed()) {
                Log::error('Request failed', ['response' => $response->json()]);
                return [
                    'success' => false,
                    'message' => 'Failed to send request'
                ];
            }

            Log::info('Request successful', ['response' => $response->json()]);
            return $response->json();
        } catch (\Exception $e) {
            Log::error('Request exception', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ];
        }
    }
}
