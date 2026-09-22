<?php

namespace App\Events;

use App\Models\Rating;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RatingUpdated
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Rating $rating,
    ) {}
}
