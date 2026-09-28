<?php

namespace App\Events;

use App\Models\BookingPayment;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BookingPaymentUploaded
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(public BookingPayment $payment) {}
}