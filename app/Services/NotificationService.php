<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class NotificationService
{
    private $apiUrl;
    private $apiKey;

    public function __construct()
    {
        $this->apiUrl = env('WHATSAPP_API_URL', 'https://ad.adkombo.com/api/whatsapp/send');
        $this->apiKey = env('WHATSAPP_API_KEY', 'e1961a42-abd3-4f32-80f8-54d24d86a6c5');
    }

    /**
     * Send a WhatsApp message
     * 
     * @param array $contacts
     * @return array
     */
    public function sendWhatsAppMessage(array $contacts)
    {
        // Prepare the data
        $postData = [
            'contact' => $contacts
        ];



        Log::alert('Post Data: ', $postData);

        return;

        // Initialize cURL
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $this->apiUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($postData),
            CURLOPT_HTTPHEADER => [
                "Api-key: {$this->apiKey}",
                'Content-Type: application/json',
            ],
        ]);

        // Execute the cURL request
        $response = curl_exec($curl);

        // Check for cURL errors
        if (curl_errno($curl)) {
            curl_close($curl);
            return [
                'success' => false,
                'message' => 'cURL error: ' . curl_error($curl)
            ];
        }

        // Decode the response
        $decodedResponse = json_decode($response, true);
        curl_close($curl);

        // dd($decodedResponse);
        return $decodedResponse;
    }
}
