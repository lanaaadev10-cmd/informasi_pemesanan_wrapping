<?php

namespace App\Http\Controllers;

/**
 * PelangganController — Controller resmi dalam bahasa Indonesia untuk area pelanggan dan dashboard customer.
 * Mewarisi CustomerController untuk menjamin kompatibilitas penuh.
 */
class PelangganController extends CustomerController
{
}

if (!class_exists('App\Http\Controllers\PelangganController', false)) {
    class_alias(CustomerController::class, 'App\Http\Controllers\PelangganController');
}
