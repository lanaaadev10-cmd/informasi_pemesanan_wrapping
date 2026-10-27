<?php

namespace App\Http\Controllers;

/**
 * BerandaController — Controller resmi dalam bahasa Indonesia untuk landing page publik, profil perusahaan,
 * informasi tentang kami, katalog layanan publik, dan kebijakan privasi.
 * Mewarisi LandingPageController untuk menjamin kompatibilitas penuh.
 */
class BerandaController extends LandingPageController
{
    /**
     * Tampilkan halaman utama / beranda website (Bahasa Indonesia).
     */
    public function beranda()
    {
        return $this->index();
    }

    /**
     * Tampilkan halaman profil perusahaan (Bahasa Indonesia).
     */
    public function profilPerusahaan()
    {
        return $this->profile();
    }
}

if (!class_exists('App\Http\Controllers\BerandaController', false)) {
    class_alias(LandingPageController::class, 'App\Http\Controllers\BerandaController');
}
