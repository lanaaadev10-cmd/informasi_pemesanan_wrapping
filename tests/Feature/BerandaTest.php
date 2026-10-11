<?php

use App\Settings\CompanySettings;

test('halaman beranda utama dapat diakses dengan sukses', function () {
    $settings = app(CompanySettings::class);
    $settings->nama_perusahaan = 'Dantie Sticker';
    $settings->deskripsi = 'Solusi stiker terbaik kendaraan Anda.';
    $settings->alamat = 'Jl. Raya Wrapping No. 77';
    $settings->email = 'info@dantiesticker.com';
    $settings->nomor_telepon = '081234567890';
    $settings->save();

    $response = $this->get('/');

    $response->assertStatus(200);
    $response->assertSee('Dantie Sticker');
    $response->assertSee('081234567890');
});
