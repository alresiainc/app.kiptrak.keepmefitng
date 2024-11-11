<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Order Statuses
    |--------------------------------------------------------------------------
    |
    | This array contains all the possible order statuses used in the application.
    | These statuses can be used throughout the app and can be updated easily.
    |
    */
    'order_statuses' => [
        'new' => 'New',
        'pending' => 'Pending',
        'cancelled' => 'Cancelled',
        'delivered_not_remitted' => 'Delivered Not Remitted',
        'delivered_and_remitted' => 'Delivered & Remitted',
        'order_in_transit' => 'Order In Transit',
        'rescheduled_order' => 'Rescheduled Order'
    ],

    /*
    |--------------------------------------------------------------------------
    | Notification Channels
    |--------------------------------------------------------------------------
    |
    | These are the different communication channels used for notifications.
    | The channels listed here should match the ones used in your system.
    |
    */
    'notification_channels' => [
        'whatsapp',
        'sms',
        'email',
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Country Code
    |--------------------------------------------------------------------------
    |
    | The default country code used for phone numbers in the application.
    |
    */
    'default_country_code' => '+234',

    /*
    |--------------------------------------------------------------------------
    | Supported Countries
    |--------------------------------------------------------------------------
    |
    | This array contains the supported countries for phone number validation.
    | Each entry includes the country code, local number length, and regex pattern
    | for validating phone numbers with the country code.
    |
    */
    'supported_countries' => [
        [
            'country_code' => '+234',
            'local_length' => 10, // Nigeria's local phone number length
            'regex' => '/^234\d{10}$/', // Regex for Nigerian phone numbers with country code
        ],
        [
            'country_code' => '+44',
            'local_length' => 10, // UK's local phone number length
            'regex' => '/^44\d{10}$/', // Regex for UK phone numbers with country code
        ],
        // Add more countries as needed
    ],

    /*
    |--------------------------------------------------------------------------
    | WhatsApp Notification Configuration (Adkombo)
    |--------------------------------------------------------------------------
    |
    | The default configuration for WhatsApp notifications through the Adkombo API.
    | Includes the session name and API key for sending messages via WhatsApp.
    |
    */

    'adkombo_whatsapp' => [
        'api_url' => 'https://ad.adkombo.com/api/whatsapp/send',
        'default_session_name' => env("ADKOMBO_SESSION_NAME"),
        'api_key' => env('ADKOMBO_API_KEY'),

    ],

    /*
    |--------------------------------------------------------------------------
    | SMS Notification Configuration (BulkSMSNigeria)
    |--------------------------------------------------------------------------
    |
    | The default configuration for SMS notifications through the BulkSMSNigeria API.
    | Includes the API token for sending SMS messages.
    |
    */
    'bulk_sms_nigeria' => [
        'api_url' => 'https://www.bulksmsnigeria.com/api/v1/sms/create',
        'api_token' => env('BULK_SMS_NIGERIA_API_TOKEN'),
        'sender_name' => env('SMS_SENDER_NAME', 'KIPTRAK'),
    ],

];
