<?php

namespace App\Helpers\Traits;

/**
 * Trait penyedia data statis untuk galeri dan tim.
 */
trait ProvidesStaticMediaData
{
    /**
     * Gallery image URL mapping — local placeholder path → local image asset.
     */
    private static array $galeriFotoMap = [
        'images/galeri/tesla-model-s.jpg'     => '/images/tesla_model_s.webp',
        'images/galeri/range-rover-sport.jpg' => '/images/hero_racing_car.webp',
        'images/galeri/ferrari-f8.jpg'        => '/images/banner_exclusive_car.webp',
        'images/galeri/porsche-911.jpg'       => '/images/hero_car.webp',
        'images/galeri/mercedes-s-class.jpg'  => '/images/banner_exclusive_car.webp',
        'images/galeri/lamborghini-urus.jpg'  => '/images/hero_racing_car.webp',
    ];

    /**
     * Resolve gallery image URL: local placeholder path → local asset URL.
     */
    public static function galeriFoto(string $path): string
    {
        return self::$galeriFotoMap[$path]
            ?? self::$galeriFotoMap['images/galeri/tesla-model-s.jpg']
            ?? '/images/hero_car.webp';
    }

    /**
     * Static gallery items.
     * Each item: ['judul', 'foto', 'deskripsi', 'kategori', 'badge_text']
     */
    public static function galeriItems(): array
    {
        return [
            [
                'judul'      => self::GALERI_ITEM_1_JUDUL,
                'foto'       => 'images/galeri/tesla-model-s.jpg',
                'deskripsi'  => self::GALERI_ITEM_1_DESC,
                'kategori'   => 'matte',
                'badge_text' => self::GALERI_ITEM_1_BADGE,
            ],
            [
                'judul'      => self::GALERI_ITEM_2_JUDUL,
                'foto'       => 'images/galeri/range-rover-sport.jpg',
                'deskripsi'  => self::GALERI_ITEM_2_DESC,
                'kategori'   => 'satin',
                'badge_text' => self::GALERI_ITEM_2_BADGE,
            ],
            [
                'judul'      => self::GALERI_ITEM_3_JUDUL,
                'foto'       => 'images/galeri/ferrari-f8.jpg',
                'deskripsi'  => self::GALERI_ITEM_3_DESC,
                'kategori'   => 'satin',
                'badge_text' => '',
            ],
            [
                'judul'      => self::GALERI_ITEM_4_JUDUL,
                'foto'       => 'images/galeri/porsche-911.jpg',
                'deskripsi'  => self::GALERI_ITEM_4_DESC,
                'kategori'   => 'matte',
                'badge_text' => self::GALERI_ITEM_4_BADGE,
            ],
            [
                'judul'      => self::GALERI_ITEM_5_JUDUL,
                'foto'       => 'images/galeri/mercedes-s-class.jpg',
                'deskripsi'  => self::GALERI_ITEM_5_DESC,
                'kategori'   => 'glossy',
                'badge_text' => '',
            ],
            [
                'judul'      => self::GALERI_ITEM_6_JUDUL,
                'foto'       => 'images/galeri/lamborghini-urus.jpg',
                'deskripsi'  => self::GALERI_ITEM_6_DESC,
                'kategori'   => 'satin',
                'badge_text' => self::GALERI_ITEM_6_BADGE,
            ],
        ];
    }

    /**
     * Gallery filter categories.
     */
    public static function galeriCategories(): array
    {
        return [
            ['slug' => 'matte',  'label' => 'Matte Series'],
            ['slug' => 'satin',  'label' => 'Satin Series'],
            ['slug' => 'glossy', 'label' => 'Glossy Series'],
        ];
    }

    /**
     * Team members.
     */
    public static function teamMembers(): array
    {
        return [
            [
                'nama'        => 'Dantie',
                'posisi'      => 'Founder & Master Installer',
                'foto'        => 'images/team/dantie.webp',
                'pengalaman'  => '10+ Tahun',
                'spesialisasi'=> 'Full Body Wrap & PPF',
            ],
            [
                'nama'        => 'Rian',
                'posisi'      => 'Admin & Creative Director',
                'foto'        => 'images/team/rian.webp',
                'pengalaman'  => '5+ Tahun',
                'spesialisasi'=> 'Customer Care & Consultation',
            ],
            [
                'nama'        => 'Dimas',
                'posisi'      => 'Senior Wrapping Specialist',
                'foto'        => 'images/team/dimas.webp',
                'pengalaman'  => '7+ Tahun',
                'spesialisasi'=> 'Precision Knifeless Cutting',
            ],
            [
                'nama'        => 'Aris',
                'posisi'      => 'Quality Control & Finishing',
                'foto'        => 'images/team/aris.webp',
                'pengalaman'  => '6+ Tahun',
                'spesialisasi'=> 'Post-Heating & Edge Seal',
            ],
        ];
    }
}
