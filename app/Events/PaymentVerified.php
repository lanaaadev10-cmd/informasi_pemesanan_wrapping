<?php

namespace App\Events;

use App\Models\Pesanan;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Event: Payment Verified
 * 
 * Triggered: Ketika admin verify payment (status = sedang_diproses)
 * Listeners: Notify customer payment verified, Start order processing
 */
class PaymentVerified
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Pesanan $pesanan,
    ) {}
}
