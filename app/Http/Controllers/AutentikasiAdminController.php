<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * AutentikasiAdminController — Controller resmi dalam bahasa Indonesia untuk pemrosesan form login administrator.
 * Mewarisi AdminAuthController untuk menjamin kompatibilitas penuh.
 */
class AutentikasiAdminController extends AdminAuthController
{
    /**
     * Memproses login administrator (Bahasa Indonesia).
     */
    public function masuk(Request $request)
    {
        return $this->login($request);
    }
}

if (!class_exists('App\Http\Controllers\AutentikasiAdminController', false)) {
    class_alias(AdminAuthController::class, 'App\Http\Controllers\AutentikasiAdminController');
}
