<?php

namespace Database\Seeders;

use App\Models\Layanan;
use Illuminate\Database\Seeder;

class LayananSeeder extends Seeder
{
    public function run(): void
    {
        $layanans = [
            [
                'nama_layanan'   => 'Variasi Mobil',
                'tipe_paket'     => 'wrapping',
                'deskripsi'      => 'Layanan custom wrapping dan stiker premium untuk variasi tampilan mobil Anda. Tersedia berbagai pilihan warna dan finishing.',
                'fitur'          => ['Material Premium Grade-A', 'Garansi 3 Tahun', 'Estimasi 3-5 Hari Kerja', 'Konsultasi Desain Gratis'],
                'harga'          => 1500000,
                'kategori'       => 'mobil',
                'estimasi_waktu' => '3-5 Hari',
                'tipe_layanan'   => 'custom',
                'foto_contoh'    => 'layanan/variasi_mobil.jpg',
            ],
            [
                'nama_layanan'   => 'Kaca Film',
                'tipe_paket'     => 'window-film',
                'deskripsi'      => 'Pemasangan kaca film premium dengan perlindungan UV 99% dan visibilitas optimal. Meningkatkan kenyamanan dan keamanan berkendara.',
                'fitur'          => ['Anti UV 99%', 'Garansi 5 Tahun', 'Estimasi 1 Hari Kerja', 'Peredam Panas Maksimal'],
                'harga'          => 200000,
                'kategori'       => 'mobil',
                'estimasi_waktu' => '1 Hari',
                'tipe_layanan'   => 'custom',
                'foto_contoh'    => 'layanan/kaca_film.jpg',
            ],
            [
                'nama_layanan'   => 'Audio Mobil',
                'tipe_paket'     => 'audio',
                'deskripsi'      => 'Instalasi dan tuning sistem audio mobil berkualitas tinggi. Dari penggantian speaker hingga sound system lengkap dengan akustik optimal.',
                'fitur'          => ['Sound Tuning Profesional', 'Garansi 2 Tahun', 'Estimasi 2-4 Hari Kerja', 'Konsultasi Audio Gratis'],
                'harga'          => 2000000,
                'kategori'       => 'mobil',
                'estimasi_waktu' => '2-4 Hari',
                'tipe_layanan'   => 'custom',
                'foto_contoh'    => 'layanan/audio_mobil.jpg',
            ],
        ];

        foreach ($layanans as $data) {
            Layanan::updateOrCreate(['nama_layanan' => $data['nama_layanan']], $data);
        }
    }
}
