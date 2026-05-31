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
                'nama_layanan'   => 'Full Body Wrap Standard',
                'tipe_paket'     => 'Standard',
                'deskripsi'      => 'Pembungkus bodi kendaraan menyeluruh dengan bahan vinyl berkualitas pilihan warna solid.',
                'fitur'          => [
                    'Bahan Vinyl Lokal',
                    'Anti Goresan',
                    'Garansi 1 Tahun',
                    'Pilihan Warna Solid',
                    'Pengerjaan Rapi',
                ],
                'harga'          => 3500000,
                'kategori'       => 'mobil',
                'estimasi_waktu' => '3-5 Hari Kerja',
                'foto_contoh'    => 'layanan/Full body wrap.jpg',
                'tipe_layanan'   => 'fix',
            ],
            [
                'nama_layanan'   => 'Partial Wrap Kap Mesin',
                'tipe_paket'     => 'Standard',
                'deskripsi'      => 'Pemasangan vinyl pada bagian kap mesin kendaraan untuk tampilan sporty dan perlindungan cat.',
                'fitur'          => [
                    'Bahan Vinyl Import',
                    'Anti UV',
                    'Garansi 1 Tahun',
                    'Finishing Glossy/Matte',
                    'Pemasangan Presisi',
                ],
                'harga'          => 850000,
                'kategori'       => 'mobil',
                'estimasi_waktu' => '1-2 Hari Kerja',
                'foto_contoh'    => 'layanan/Partial Wrap Kap Mesin.jpg',
                'tipe_layanan'   => 'fix',
            ],
            [
                'nama_layanan'   => 'Wrapping Motor Full Body',
                'tipe_paket'     => 'Premium',
                'deskripsi'      => 'Pembungkus bodi motor secara menyeluruh dengan bahan vinyl premium tahan lama.',
                'fitur'          => [
                    'Bahan Vinyl 3M',
                    'Anti UV & Goresan',
                    'Garansi 2 Tahun',
                    'Free Desain Custom',
                    'Pengerjaan Profesional',
                ],
                'harga'          => 1500000,
                'kategori'       => 'motor',
                'estimasi_waktu' => '2-3 Hari Kerja',
                'foto_contoh'    => 'layanan/Wrapping Motor Full Body.jpg',
                'tipe_layanan'   => 'fix',
            ],
            [
                'nama_layanan'   => 'Sticker Branding Kendaraan',
                'tipe_paket'     => 'Custom',
                'deskripsi'      => 'Pemasangan sticker branding usaha atau promosi pada kendaraan roda dua maupun roda empat.',
                'fitur'          => [
                    'Cetak Digital Resolusi Tinggi',
                    'Bahan Anti Air',
                    'Free Desain Logo',
                    'Warna Tajam & Tahan Lama',
                    'Konsultasi Desain Gratis',
                ],
                'harga'          => 500000,
                'kategori'       => 'motor',
                'estimasi_waktu' => '1-2 Hari Kerja',
                'foto_contoh'    => 'layanan/Sticker Branding Kendaraan.jpg',
                'tipe_layanan'   => 'custom',
            ],
            [
                'nama_layanan'   => 'Roof Wrap & Panoramic',
                'tipe_paket'     => 'Standard',
                'deskripsi'      => 'Pemasangan vinyl khusus pada bagian atap kendaraan untuk tampilan dua warna yang stylish.',
                'fitur'          => [
                    'Bahan Vinyl Import',
                    'Anti UV',
                    'Garansi 1 Tahun',
                    'Pilihan Warna Beragam',
                    'Pemasangan Bubble-Free',
                ],
                'harga'          => 950000,
                'kategori'       => 'mobil',
                'estimasi_waktu' => '1 Hari Kerja',
                'foto_contoh'    => 'layanan/Roof Wrap & Panoramic.jpg',
                'tipe_layanan'   => 'fix',
            ],
        ];

        foreach ($layanans as $layanan) {
            Layanan::updateOrCreate(
                ['nama_layanan' => $layanan['nama_layanan']],
                $layanan
            );
        }
    }
}