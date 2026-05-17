<?php

use App\Models\ProfilPerusahaan;
use Database\Seeders\ProfilPerusahaanSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can fetch the company profile using the singleton helper', function () {
    // Awalnya kosong
    expect(ProfilPerusahaan::singleton())->toBeNull();

    // Buat profil perusahaan baru
    $profile = ProfilPerusahaan::create([
        'nama_perusahaan' => 'Dantie Sticker',
        'deskripsi' => 'Solusi stiker terbaik kendaraan Anda.',
        'alamat' => 'Jl. Raya Wrapping No. 77',
        'email' => 'info@dantiesticker.com',
        'nomor_telepon' => '081234567890',
    ]);

    // Ambil via singleton
    $fetched = ProfilPerusahaan::singleton();
    expect($fetched)->not->toBeNull();
    expect($fetched->nama_perusahaan)->toBe('Dantie Sticker');
    expect($fetched->id)->toBe($profile->id);
});

it('can generate a clean whatsapp direct link from phone number', function () {
    $profile = new ProfilPerusahaan([
        'nomor_telepon' => '0812-3456-7890',
    ]);

    // Format "08" harus diubah ke "628" dan strip tanda hubung
    expect($profile->whatsapp_link)->toBe('https://wa.me/6281234567890');

    $profile2 = new ProfilPerusahaan([
        'nomor_telepon' => '+62 877 6543 210',
    ]);

    expect($profile2->whatsapp_link)->toBe('https://wa.me/628776543210');
});

it('can get logo url when logo is set', function () {
    $profileWithoutLogo = new ProfilPerusahaan([
        'logo' => null,
    ]);
    expect($profileWithoutLogo->logo_url)->toBeNull();

    $profileWithLogo = new ProfilPerusahaan([
        'logo' => 'logos/example.png',
    ]);
    expect($profileWithLogo->logo_url)->toContain('storage/logos/example.png');
});

it('can run profil perusahaan seeder successfully', function () {
    // Jalankan seeder
    $this->seed(ProfilPerusahaanSeeder::class);

    // Pastikan data berhasil masuk
    $profile = ProfilPerusahaan::first();
    expect($profile)->not->toBeNull();
    expect($profile->nama_perusahaan)->toBe('Dantie Sticker');
    expect($profile->email)->toBe('info@dantiesticker.com');
    expect($profile->visi)->toContain('kualitas internasional');
});
