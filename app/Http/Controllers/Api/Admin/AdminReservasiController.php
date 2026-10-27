<?php

namespace App\Http\Controllers\Api\Admin;

/**
 * AdminReservasiController — REST API Controller resmi dalam bahasa Indonesia untuk manajemen reservasi oleh Administrator.
 * Mewarisi AdminBookingController untuk menjamin kompatibilitas penuh.
 */
class AdminReservasiController extends AdminBookingController
{
}

if (!class_exists('App\Http\Controllers\Api\Admin\AdminReservasiController', false)) {
    class_alias(AdminBookingController::class, 'App\Http\Controllers\Api\Admin\AdminReservasiController');
}
