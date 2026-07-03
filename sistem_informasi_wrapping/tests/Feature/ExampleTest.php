<?php

use App\Settings\CompanySettings;

it('returns a successful response', function () {
    $settings = app(CompanySettings::class);
    $settings->nama_perusahaan = 'Dantie Sticker';
    $settings->deskripsi = 'Solusi stiker terbaik kendaraan Anda.';
    $settings->alamat = 'Jl. Raya Wrapping No. 77';
    $settings->email = 'info@dantiesticker.com';
    $settings->nomor_telepon = '081234567890';
    $settings->save();

    $response = $this->get('/');

    $response->assertStatus(200);
});
