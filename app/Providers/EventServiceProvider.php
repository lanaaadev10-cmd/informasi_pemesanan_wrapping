<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

// Events
use App\Events\OrderCreated;
use App\Events\OrderConfirmed;
use App\Events\OrderCompleted;
use App\Events\OrderRejected;
use App\Events\PaymentUploaded;
use App\Events\PaymentVerified;
use App\Events\BookingCreated;
use App\Events\BookingConfirmed;
use App\Events\BookingPaymentUploaded;
use App\Events\BookingPaymentVerified;
use App\Events\BookingRejected;
use App\Events\BookingCompleted;

// Listeners
use App\Listeners\SendOrderConfirmationEmail;
use App\Listeners\SendOrderCreatedToAdmin;
use App\Listeners\NotifyPaymentRequired;
use App\Listeners\NotifyOrderProcessingStarted;
use App\Listeners\NotifyOrderCompleted;
use App\Listeners\NotifyOrderRejection;
use App\Listeners\SendPaymentUploadedToAdmin;
use App\Listeners\NotifyBookingCreated;
use App\Listeners\NotifyAdminNewBooking;
use App\Listeners\NotifyBookingConfirmed;
use App\Listeners\NotifyAdminBookingPaymentUploaded;
use App\Listeners\NotifyBookingPaymentVerified;
use App\Listeners\NotifyBookingRejected;
use App\Listeners\NotifyBookingCompleted;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Map events to their listeners
     * 
     * Listeners akan di-trigger ketika event diemit
     */
    protected $listen = [
        // Order lifecycle events
        OrderCreated::class => [
            SendOrderConfirmationEmail::class,
            SendOrderCreatedToAdmin::class,
        ],

        OrderConfirmed::class => [
            NotifyPaymentRequired::class,
        ],

        PaymentVerified::class => [
            NotifyOrderProcessingStarted::class,
        ],

        OrderCompleted::class => [
            NotifyOrderCompleted::class,
        ],

        OrderRejected::class => [
            NotifyOrderRejection::class,
        ],

        PaymentUploaded::class => [
            SendPaymentUploadedToAdmin::class,
        ],

        // Booking lifecycle events
        BookingCreated::class => [
            NotifyBookingCreated::class,
            NotifyAdminNewBooking::class,
        ],

        BookingConfirmed::class => [
            NotifyBookingConfirmed::class,
        ],

        BookingPaymentUploaded::class => [
            NotifyAdminBookingPaymentUploaded::class,
        ],

        BookingPaymentVerified::class => [
            NotifyBookingPaymentVerified::class,
        ],

        BookingRejected::class => [
            NotifyBookingRejected::class,
        ],

        BookingCompleted::class => [
            NotifyBookingCompleted::class,
        ],
    ];

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return true;
    }
}
