<?php

namespace App\Http\Controllers\Api;

/**
 * ReservasiPublikController — REST API Controller resmi untuk data kuota publik reservasi workshop.
 * Mewarisi BookingPublicController untuk menjamin kompatibilitas penuh kalender landing & dashboard.
 */
class ReservasiPublikController extends BookingPublicController
{
}

if (!class_exists('App\Http\Controllers\Api\ReservasiPublikController', false)) {
    class_alias(BookingPublicController::class, 'App\Http\Controllers\Api\ReservasiPublikController');
}
