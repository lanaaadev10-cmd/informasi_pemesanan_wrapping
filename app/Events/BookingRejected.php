<?php

namespace App\Events;

use App\Models\Booking;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BookingRejected
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public Booking $booking,
        public ?string $alasan = null,
    ) {}
}