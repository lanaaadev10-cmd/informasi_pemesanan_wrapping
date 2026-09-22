<?php

namespace App\Providers;

use App\Events\OrderCompleted;
// Events
use App\Events\OrderConfirmed;
use App\Events\OrderCreated;
use App\Events\OrderRejected;
use App\Events\PaymentUploaded;
use App\Events\PaymentVerified;
use App\Events\RatingCreated;
use App\Events\RatingUpdated;
use App\Listeners\NotifyAdminRating;
// Listeners
use App\Listeners\NotifyOrderCompleted;
use App\Listeners\NotifyOrderProcessingStarted;
use App\Listeners\NotifyOrderRejection;
use App\Listeners\NotifyPaymentRequired;
use App\Listeners\SendOrderConfirmationEmail;
use App\Listeners\SendOrderCreatedToAdmin;
use App\Listeners\SendPaymentUploadedToAdmin;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

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

        // Rating events
        RatingCreated::class => [
            NotifyAdminRating::class,
        ],

        RatingUpdated::class => [
            NotifyAdminRating::class,
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
