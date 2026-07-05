<?php

namespace App\Events;

use App\Models\Pesanan;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Event: Order Confirmed
 * 
 * Triggered: Ketika admin confirm order (status = menunggu_pembayaran)
 * Listeners: Notify customer to upload payment, Email notification
 */
class OrderConfirmed
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Pesanan $pesanan,
    ) {}
}
