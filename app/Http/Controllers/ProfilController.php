<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * ProfilController — Controller resmi dalam bahasa Indonesia untuk manajemen profil pengguna.
 * Mewarisi ProfileController untuk menjamin kompatibilitas penuh dengan rute dan framework Breeze.
 */
class ProfilController extends ProfileController
{
    /**
     * Tampilkan formulir edit profil pengguna (Bahasa Indonesia).
     */
    public function tampilkan(Request $request): View
    {
        return $this->edit($request);
    }

    /**
     * Perbarui data profil pengguna (Bahasa Indonesia).
     */
    public function perbarui(ProfileUpdateRequest $request): RedirectResponse
    {
        return $this->update($request);
    }

    /**
     * Hapus akun pengguna secara permanen (Bahasa Indonesia).
     */
    public function hapusAkun(Request $request): RedirectResponse
    {
        return $this->destroy($request);
    }
}

if (!class_exists('App\Http\Controllers\ProfilController', false)) {
    class_alias(ProfileController::class, 'App\Http\Controllers\ProfilController');
}
