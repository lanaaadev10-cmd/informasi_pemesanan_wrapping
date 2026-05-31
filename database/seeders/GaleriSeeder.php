<?php

namespace Database\Seeders;

use App\Models\Galeri;
use Illuminate\Database\Seeder;

class GaleriSeeder extends Seeder
{
    public function run(): void
    {
        $galeris = [
            [
                'judul'          => 'Stiker Body Motor NMAX',
                'sub_judul'      => 'Racing Stripe Design',
                'kategori'       => 'stiker',
                'foto'           => 'galeri/Stiker Body Motor NMAX.jpg', // <<< NANTI ISI MANUAL
                'deskripsi'      => 'Pemasangan stiker racing stripe pada Yamaha NMAX dengan kombinasi warna hitam dan merah.',
                'tanggal_upload' => '2026-05-15',
                'is_featured'    => false,
                'badge_text'     => 'Stiker',
            ],
            [
                'judul'          => 'Full Body Wrap Avanza Matte Black',
                'sub_judul'      => 'Matte Black Edition',
                'kategori'       => 'wrapping',
                'foto'           => 'galeri/Full Body Wrap Avanza Matte Black.jpg', // <<< NANTI ISI MANUAL
                'deskripsi'      => 'Transformasi total Toyota Avanza dengan vinyl matte hitam premium, tampilan elegan dan sporty.',
                'tanggal_upload' => '2026-05-10',
                'is_featured'    => true,
                'badge_text'     => 'Wrapping',
            ],
            [
                'judul'          => 'Chrome Delete Toyota Fortuner',
                'sub_judul'      => 'Full Blackout Trim',
                'kategori'       => 'chrome_delete',
                'foto'           => 'galeri/Chrome Delete Toyota Fortuner.jpg', // <<< NANTI ISI MANUAL
                'deskripsi'      => 'Penggantian seluruh bagian chrome Fortuner menjadi hitam matte untuk tampilan gagah dan sporty.',
                'tanggal_upload' => '2026-05-05',
                'is_featured'    => true,
                'badge_text'     => 'Chrome Delete',
            ],
            [
                'judul'          => 'Full Body Wrap Motor PCX',
                'sub_judul'      => 'Custom Grafis Dua Warna',
                'kategori'       => 'wrapping',
                'foto'           => 'galeri/Modifikasi PCX.jpg', // <<< NANTI ISI MANUAL
                'deskripsi'      => 'Pemasangan vinyl full body Honda PCX dengan motif grafis dua warna kombinasi biru dan putih.',
                'tanggal_upload' => '2026-04-20',
                'is_featured'    => false,
                'badge_text'     => 'Wrapping',
            ],
            [
                'judul'          => 'Partial Wrap Kap Mesin Xpander',
                'sub_judul'      => 'Hood Glossy Black',
                'kategori'       => 'wrapping',
                'foto'           => 'galeri/Partial Wrap Kap Mesin Xpander.jpg', // <<< NANTI ISI MANUAL
                'deskripsi'      => 'Pemasangan vinyl hitam glossy pada kap mesin Mitsubishi Xpander untuk aksen sporty dua warna.',
                'tanggal_upload' => '2026-04-15',
                'is_featured'    => false,
                'badge_text'     => 'Partial Wrap',
            ],
        ];

        foreach ($galeris as $galeri) {
            Galeri::updateOrCreate(
                ['judul' => $galeri['judul']],
                $galeri
            );
        }
    }
}