<?php

namespace App\Services;

/**
 * PenilaianService — Layanan utama untuk moderasi, penyimpanan, dan pengelolaan ulasan & rating.
 * Mewarisi RatingService untuk kompatibilitas penuh.
 */
class PenilaianService extends RatingService
{
}

if (!class_exists('App\Services\PenilaianService', false)) {
    class_alias(RatingService::class, 'App\Services\PenilaianService');
}
