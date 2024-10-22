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

    'default_country_code' => '+234',
    'supported_countries' => [
        [
            'country_code' => '+234',
            'local_length' => 10, // Expected length of local numbers (Nigeria)
            'regex' => '/^234\d{10}$/', // Nigeria's phone numbers with country code
        ],
        [
            'country_code' => '+44',
            'local_length' => 10, // UK example
            'regex' => '/^44\d{10}$/', // UK phone numbers with country code
        ],
        // Add more countries as needed
    ],


    /*
    |--------------------------------------------------------------------------
    | WhatsApp Notification Configuration (adkombo)
    |--------------------------------------------------------------------------
    |
    | These are the different communication channels used for notifications.
    | The channels listed here should match the ones used in your system.
    |
    */

    'default_adkombo_whatsapp_session_name' => '3560919_Test WhatsApp Device',
    'adkombo_api_key' => 'e1961a42-abd3-4f32-80f8-54d24d86a6c5'

];
