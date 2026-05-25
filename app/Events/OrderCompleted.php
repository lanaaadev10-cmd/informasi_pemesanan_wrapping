<?php

namespace App\Events;

use App\Models\Pesanan;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Event: Order Completed
 * 
 * Triggered: Ketika admin mark order as selesai
 * Listeners: Send completion email, Send SMS notification, Log completion
 */
class OrderCompleted
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Pesanan $pesanan,
    ) {}
}
