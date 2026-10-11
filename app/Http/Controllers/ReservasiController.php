<?php

namespace App\Http\Controllers;

/**
 * ReservasiController — Controller resmi dalam bahasa Indonesia untuk modul reservasi wrapping.
 * Mewarisi BookingController untuk menjamin kompatibilitas penuh seluruh alur frontend dan middleware.
 */
class ReservasiController extends BookingController
{
}

if (!class_exists('App\Http\Controllers\ReservasiController', false)) {
    class_alias(BookingController::class, 'App\Http\Controllers\ReservasiController');
}
