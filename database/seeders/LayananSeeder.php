<?php

namespace Database\Seeders;

use App\Models\Layanan;
use Illuminate\Database\Seeder;

class LayananSeeder extends Seeder
{
    public function run(): void
    {
        $layanans = [
            # Layanan Mobil
            [
                'nama_layanan'   => 'Variasi Mobil',
                'tipe_paket'     => 'wrapping',
                'deskripsi'      => 'Layanan custom wrapping dan stiker premium untuk variasi tampilan mobil Anda. Tersedia berbagai pilihan warna dan finishing.',
                'fitur'          => ['Material Premium Grade-A', 'Garansi 3 Tahun', 'Estimasi 3-5 Hari Kerja', 'Konsultasi Desain Gratis'],
                'harga'          => 1500000,
                'kategori'       => 'mobil',
                'estimasi_waktu' => '3-5 Hari',
                'tipe_layanan'   => 'custom',
                'foto_contoh'    => 'images/layanan/wrapping-mobil.jpg',
            ],
            # Layanan Kaca Film
            [
                'nama_layanan'   => 'Kaca Film',
                'tipe_paket'     => 'window-film',
                'deskripsi'      => 'Pemasangan kaca film premium dengan perlindungan UV 99% dan visibilitas optimal. Meningkatkan kenyamanan dan keamanan berkendara.',
                'fitur'          => ['Anti UV 99%', 'Garansi 5 Tahun', 'Estimasi 1 Hari Kerja', 'Peredam Panas Maksimal'],
                'harga'          => 200000,
                'kategori'       => 'mobil',
                'estimasi_waktu' => '1 Hari',
                'tipe_layanan'   => 'custom',
                'foto_contoh'    => 'images/layanan/kaca-film.jpg',
            ],
            # Layanan Audio Mobil
            [
                'nama_layanan'   => 'Audio Mobil',
                'tipe_paket'     => 'audio',
                'deskripsi'      => 'Instalasi dan tuning sistem audio mobil berkualitas tinggi. Dari penggantian speaker hingga sound system lengkap dengan akustik optimal.',
                'fitur'          => ['Sound Tuning Profesional', 'Garansi 2 Tahun', 'Estimasi 2-4 Hari Kerja', 'Konsultasi Audio Gratis'],
                'harga'          => 2000000,
                'kategori'       => 'mobil',
                'estimasi_waktu' => '2-4 Hari',
                'tipe_layanan'   => 'custom',
                'foto_contoh'    => 'images/layanan/audio-head.jpg',
            ],
            # Layanan Motor
            [
                'nama_layanan'   => 'Variasi Motor',
                'tipe_paket'     => 'wrapping',
                'deskripsi'      => 'Layanan custom wrapping dan stiker premium untuk variasi tampilan motor Anda. Tersedia berbagai pilihan warna dan finishing.',
                'fitur'          => ['Material Premium Grade-A', 'Garansi 3 Tahun', 'Estimasi 2-3 Hari Kerja', 'Konsultasi Desain Gratis'],
                'harga'          => 800000,
                'kategori'       => 'motor',
                'estimasi_waktu' => '2-3 Hari',
                'tipe_layanan'   => 'custom',
                'foto_contoh'    => 'images/layanan/wrapping-motor.jpg',
            ],
            # Layanan Lampu Biled
            [
                'nama_layanan'   => 'Lampu Biled',
                'tipe_paket'     => 'lighting',
                'deskripsi'      => 'Penjualan dan perbaikan lampu biled premium untuk meningkatkan visibilitas dan keselamatan berkendara.',
                'fitur'          => ['Lampu LED Premium', 'Garansi 1 Tahun', 'Estimasi 1 Hari Kerja', 'Instalasi Profesional'],
                'harga'          => 500000,
                'kategori'       => 'mobil',
                'estimasi_waktu' => '1 Hari',
                'tipe_layanan'   => 'custom',
                'foto_contoh'    => 'images/layanan/lampu-biled.jpg',
            ],
            [
            #layanan striping
                'nama_layanan'   => 'Striping',
                'tipe_paket'     => 'striping',
                'deskripsi'      => 'Pemasangan striping premium untuk meningkatkan estetika mobil Anda. Dengan striping, Anda dapat menambahkan tampilan unik dan unik ke mobil Anda.',
                'fitur'          => ['Striping Premium', 'Garansi 1 Tahun', 'Estimasi 1 Hari Kerja', 'Peredam Panas Maksimal'],
                'harga'          => 200000,
                'kategori'       => 'mobil',
                'estimasi_waktu' => '1 Hari',
                'tipe_layanan'   => 'custom',
                'foto_contoh'    => 'images/layanan/layanan-striping.jpg',
            ]
        ];

        foreach ($layanans as $data) {
            Layanan::updateOrCreate(['nama_layanan' => $data['nama_layanan']], $data);
        }
    }
}
