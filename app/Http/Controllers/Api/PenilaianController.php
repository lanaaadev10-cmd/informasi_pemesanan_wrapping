<?php

namespace App\Http\Controllers\Api;

/**
 * PenilaianController — REST API Controller resmi dalam bahasa Indonesia untuk modul ulasan & penilaian (rating).
 * Mewarisi RatingController untuk menjamin kompatibilitas penuh.
 */
class PenilaianController extends RatingController
{
}

if (!class_exists('App\Http\Controllers\Api\PenilaianController', false)) {
    class_alias(RatingController::class, 'App\Http\Controllers\Api\PenilaianController');
}
