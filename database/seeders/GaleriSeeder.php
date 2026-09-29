<?php

namespace Database\Seeders;

use App\Models\Galeri;
use Illuminate\Database\Seeder;

class GaleriSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'judul'      => 'Porsche 911 GT3',
                'foto'       => 'images/layanan/wrapping-mobil.jpg',
                'deskripsi'  => 'Matte Racing Green — aggressive yet elegant, track-ready aesthetic.',
                'kategori'   => 'matte',
                'badge_text' => 'Unggulan',
            ],
            [
                'judul'      => 'Mercedes-Benz S-Class',
                'foto'       => 'images/banner_exclusive_car.jpg',
                'deskripsi'  => 'Gloss Diamond White — mirror finish that exudes pure luxury.',
                'kategori'   => 'glossy',
                'badge_text' => 'Featured Project',
            ],
            [
                'judul'      => 'Lamborghini Urus',
                'foto'       => 'images/hero_racing_car.jpg',
                'deskripsi'  => 'Satin Armour Grey — stealthy SUV wrap with custom carbon accents.',
                'kategori'   => 'satin',
                'badge_text' => 'Best Seller',
            ],
        ];

        foreach ($items as $item) {
            Galeri::updateOrCreate(
                ['judul' => $item['judul']],
                [
                    'foto'           => $item['foto'],
                    'deskripsi'      => $item['deskripsi'],
                    'kategori'       => $item['kategori'],
                    'badge_text'     => $item['badge_text'],
                    'tanggal_upload' => now(),
                ]
            );
        }
    }
}