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
                'foto'       => 'https://images.unsplash.com/photo-1614162692292-7ac56d7f7f1e?w=600&q=80',
                'deskripsi'  => 'Matte Racing Green — aggressive yet elegant, track-ready aesthetic.',
                'kategori'   => 'matte',
                'badge_text' => 'Unggulan',
            ],
            [
                'judul'      => 'Mercedes-Benz S-Class',
                'foto'       => 'https://images.unsplash.com/photo-1618843479313-40f8afb4b4d8?w=600&q=80',
                'deskripsi'  => 'Gloss Diamond White — mirror finish that exudes pure luxury.',
                'kategori'   => 'glossy',
                'badge_text' => 'Featured Project',
            ],
            [
                'judul'      => 'Lamborghini Urus',
                'foto'       => 'https://images.unsplash.com/photo-1544636331-e26879cd4d9b?w=600&q=80',
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