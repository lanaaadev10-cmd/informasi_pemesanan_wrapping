<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Application-wide Settings
    |--------------------------------------------------------------------------
    |
    | This file contains application-specific settings for caching, pagination,
    | and other configurable parameters.
    |
    */

    'cache' => [
        // Dashboard cache duration in seconds
        'dashboard_stats' => (int) env('CACHE_DASHBOARD_STATS', 3600),
        'dashboard_chart_data' => (int) env('CACHE_DASHBOARD_CHART_DATA', 3600),

        // API cache durations
        'api_keranjang' => (int) env('CACHE_API_KERANJANG', 60),
        'api_layanan' => (int) env('CACHE_API_LAYANAN', 3600),
    ],

    'pagination' => [
        // Default items per page
        'default_per_page' => 15,

        // Maximum allowed per page to prevent abuse
        'max_per_page' => 100,

        // Unread notifications pagination
        'unread_notifications' => 15,
    ],

    'dashboard' => [
        // Number of top services to display
        'top_services_limit' => 5,

        // Monthly chart lookback in months
        'chart_months_lookback' => 12,
    ],

    'cart' => [
        // Max unique items allowed in cart
        'max_items' => (int) env('CART_MAX_ITEMS', 3),
    ],

    'payment' => [
        // Chunk size for processing multiple payments
        'chunk_size' => 100,
    ],

    'notification' => [
        // Chunk size when sending notifications to multiple admins
        'admin_notify_chunk_size' => 100,
    ],
];
