<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => env('SES_REGION', 'us-east-1'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'whatsapp' => [
        'admin_phone'  => env('WHATSAPP_ADMIN_PHONE', '6283117163605'),
        'pesan_jasa'   => 'Halo Admin, saya ingin berkonsultasi dan melakukan reservasi jasa wrapping kendaraan. Mohon informasi lebih lanjut mengenai kebutuhan dan proses pemesanannya.',
        'pesan_custom' => 'Halo Admin, saya ingin berkonsultasi mengenai wrapping custom kendaraan. Saya ingin menjelaskan kebutuhan desain dan spesifikasi terlebih dahulu.',
    ],

];
