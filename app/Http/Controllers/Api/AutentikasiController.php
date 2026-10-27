<?php

namespace App\Http\Controllers\Api;

/**
 * AutentikasiController — REST API Controller resmi dalam bahasa Indonesia untuk login, registrasi, dan profil Sanctum.
 * Mewarisi AuthController untuk menjamin kompatibilitas penuh.
 */
class AutentikasiController extends AuthController
{
}

if (!class_exists('App\Http\Controllers\Api\AutentikasiController', false)) {
    class_alias(AuthController::class, 'App\Http\Controllers\Api\AutentikasiController');
}
