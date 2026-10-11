<?php

namespace App\Http\Controllers;

/**
 * PenilaianController — Controller resmi dalam bahasa Indonesia untuk modul ulasan dan penilaian (rating).
 * Mewarisi RatingController untuk menjamin kompatibilitas penuh seluruh alur frontend dan modal review.
 */
class PenilaianController extends RatingController
{
}

if (!class_exists('App\Http\Controllers\PenilaianController', false)) {
    class_alias(RatingController::class, 'App\Http\Controllers\PenilaianController');
}
