<?php

namespace App\Events;

use App\Models\Pesanan;
use Illuminate\Broadcasting\InteractsWithBroadcasting;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Event: Order Created
 * 
 * Triggered: Ketika customer checkout (create pesanan)
 * Listeners: Email notification, Create in-app notification, Log activity
 */
class OrderCreated
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Pesanan $pesanan,
    ) {}
}
