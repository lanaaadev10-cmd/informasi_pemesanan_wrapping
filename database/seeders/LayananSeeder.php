<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LayananSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('layanans')->insert([
            [
                'nama_layanan' => 'Wrapping Full Body Mobil',
                'tipe_paket' => 'Premium',
                'deskripsi' => 'Layanan wrapping seluruh body kendaraan menggunakan material premium dengan hasil presisi dan elegan.',
                'fitur' => json_encode([
                    'Material Premium',
                    'Garansi 1 Tahun',
                    'Pengerjaan Presisi',
                    'Konsultasi Gratis'
                ]),
                'harga' => 8500000,
                'kategori' => 'mobil',
                'estimasi_waktu' => '3 Hari',
                'tipe_layanan' => 'fix',
                'foto_contoh' => 'layanan/admin/01KSKMP4B5GFE2QYS2V4GP09JX.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'nama_layanan' => 'Wrapping Motor',
                'tipe_paket' => 'Standard',
                'deskripsi' => 'Wrapping motor sport dengan pilihan warna dan desain sesuai kebutuhan pelanggan.',
                'fitur' => json_encode([
                    'Custom Design',
                    'Material Berkualitas',
                    'Finishing Rapi',
                    'Garansi 6 Bulan'
                ]),
                'harga' => 1500000,
                'kategori' => 'motor',
                'estimasi_waktu' => '1 Hari',
                'tipe_layanan' => 'fix',
                'foto_contoh' => 'layanan/admin/01KSKMM0TH4NYPD739VDWB5N23.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'nama_layanan' => 'Custom Sticker Branding',
                'tipe_paket' => 'Custom',
                'deskripsi' => 'Pembuatan sticker branding kendaraan untuk kebutuhan promosi usaha dan perusahaan.',
                'fitur' => json_encode([
                    'Desain Custom',
                    'Survey Lokasi',
                    'Material Outdoor',
                    'Konsultasi Gratis'
                ]),
                'harga' => null,
                'kategori' => 'mobil',
                'estimasi_waktu' => '2-5 Hari',
                'tipe_layanan' => 'custom',
                'foto_contoh' => 'layanan/admin/01KSKMR6RC7Y6A9174986N716D.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
