<?php

namespace App\Events;

use App\Models\Pembayaran;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Event: Payment Uploaded
 * 
 * Triggered: Ketika customer upload bukti transfer
 * Listeners: Notify admin, Create in-app notification for admin
 */
class PaymentUploaded
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Pembayaran $pembayaran,
    ) {}
}
