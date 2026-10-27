<?php

namespace App\Http\Controllers\Api;

/**
 * ReservasiController — REST API Controller resmi dalam bahasa Indonesia untuk modul reservasi pengguna.
 * Mewarisi BookingController untuk menjamin kompatibilitas penuh.
 */
class ReservasiController extends BookingController
{
}

if (!class_exists('App\Http\Controllers\Api\ReservasiController', false)) {
    class_alias(BookingController::class, 'App\Http\Controllers\Api\ReservasiController');
}
