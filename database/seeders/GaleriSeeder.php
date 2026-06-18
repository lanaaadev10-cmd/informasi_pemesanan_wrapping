<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Galeri;

class GaleriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Galeri::truncate();
        DB::table('galeris')->insert([
            [
                'judul' => 'Wrapping Toyota Fortuner',
                'sub_judul' => 'Full Body Gloss Black',
                'kategori' => 'Mobil',
                'foto' => 'galeri/01KSGTC2WTYE9AJ8RBKWGP0HB1.jpg',
                'badge_text' => 'Featured Project',
                'is_featured' => true,
                'deskripsi' => 'Pengerjaan full wrapping Toyota Fortuner menggunakan material premium gloss black dengan hasil rapi dan presisi.',
                'tanggal_upload' => '2026-06-01',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'judul' => 'Wrapping Honda PCX',
                'sub_judul' => 'Custom Racing Style',
                'kategori' => 'Motor',
                'foto' => 'galeri/01KSKN4DR3M9HWSNKG5YN3EDH7.jpg',
                'badge_text' => 'Best Design',
                'is_featured' => true,
                'deskripsi' => 'Modifikasi tampilan Honda PCX dengan konsep racing style menggunakan kombinasi warna merah dan hitam.',
                'tanggal_upload' => '2026-06-05',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'judul' => 'Wrapping Full Body',
                'sub_judul' => 'Wrapping Motor Nmax',
                'kategori' => 'Motor',
                'foto' => 'galeri/01KSKMZB04J4JCWGBA2FYKRNJ5.jpg',
                'badge_text' => 'New Project',
                'is_featured' => false,
                'deskripsi' => 'Sticker full untuk motor kesayangan anda, Bikin kendaraan anda anda rapi, enak dilihat, dan jadi lirikan para perempuan.',
                'tanggal_upload' => '2026-06-10',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
