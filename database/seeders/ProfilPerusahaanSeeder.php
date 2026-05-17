<?php

namespace Database\Seeders;

use App\Models\ProfilPerusahaan;
use Illuminate\Database\Seeder;

/**
 * Seeder untuk ProfilPerusahaan.
 *
 * Membuat 1 record profil perusahaan default.
 * Menggunakan updateOrCreate agar aman dijalankan berkali-kali.
 */
class ProfilPerusahaanSeeder extends Seeder
{
    public function run(): void
    {
        ProfilPerusahaan::updateOrCreate(
            ['id' => 1],
            [
                'nama_perusahaan'  => 'Dantie Sticker',
                'deskripsi'        => 'Solusi stiker terbaik untuk kendaraan dan bisnis Anda. Kualitas premium, hasil presisi, dan harga kompetitif.',
                'alamat'           => 'Jl. Raya Wrapping No. 77, Malang, Jawa Timur',
                'email'            => 'info@dantiesticker.com',
                'nomor_telepon'    => '081234567890',
                'logo'             => null,
                'maps_url'         => null,
                'visi'             => '<p>Menjadi perusahaan jasa wrapping dan stiker terdepan di Indonesia dengan kualitas internasional.</p>',
                'misi'             => '<ul><li>Memberikan layanan wrapping berkualitas premium</li><li>Mengutamakan kepuasan dan kepercayaan pelanggan</li><li>Berinovasi dalam desain dan teknologi wrapping</li><li>Membangun tim yang profesional dan berdedikasi</li></ul>',
                'sejarah'          => '<p>Dantie Sticker didirikan pada tahun 2020 sebagai usaha kecil di bidang stiker kendaraan. Dengan dedikasi dan kualitas yang konsisten, Dantie Sticker berkembang menjadi salah satu penyedia jasa wrapping terpercaya di Malang.</p>',
                'instagram_url'    => 'https://instagram.com/dantiesticker',
                'facebook_url'     => 'https://facebook.com/dantiesticker',
                'tiktok_url'       => 'https://tiktok.com/@dantiesticker',
                'whatsapp_url'     => 'https://wa.me/6281234567890',
                'meta_title'       => 'Dantie Sticker — Jasa Wrapping & Stiker Profesional',
                'meta_description' => 'Jasa wrapping kendaraan dan stiker berkualitas premium di Malang. Hasil presisi, harga kompetitif, dan garansi kepuasan pelanggan.',
            ]
        );
    }
}
