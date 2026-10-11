<?php

namespace App\Services;

/**
 * ReservasiService — Layanan utama siklus hidup reservasi dan booking jadwal wrapping.
 * Mewarisi BookingService untuk kompatibilitas penuh dengan seluruh trait dan logika slot.
 */
class ReservasiService extends BookingService
{
}

if (!class_exists('App\Services\ReservasiService', false)) {
    class_alias(BookingService::class, 'App\Services\ReservasiService');
}
