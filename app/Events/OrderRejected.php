<?php

namespace App\Events;

use App\Models\Pesanan;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Event: Order Rejected
 * 
 * Triggered: Ketika admin reject order atau payment rejected
 * Listeners: Notify customer rejection reason, Log rejection
 */
class OrderRejected
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Pesanan $pesanan,
    ) {}
}
