<?php

namespace Database\Seeders;

use App\Models\Addon;
use Illuminate\Database\Seeder;

class AddonSeeder extends Seeder
{
    public function run(): void
    {
        $addons = [
            [
                'name'        => 'Bongkar & Pembersihan Lem Stiker Lama (Motor)',
                'category'    => 'motor',
                'price'       => 50000,
                'description' => 'Pelepasan stiker/decal lama beserta pembersihan residu lem aspal hingga bodi steril siap dibungkus.',
                'is_active'   => true,
            ],
            [
                'name'        => 'Bongkar & Pembersihan Lem Stiker Lama (Mobil)',
                'category'    => 'mobil',
                'price'       => 350000,
                'description' => 'Pelepasan total wrapping lama mobil menggunakan cairan pembersih khusus lem tanpa mengikis cat orisinal.',
                'is_active'   => true,
            ],
            [
                'name'        => 'Nano Ceramic Coating Kaca Depan & Samping',
                'category'    => 'mobil',
                'price'       => 450000,
                'description' => 'Pelapisan hydrophobic anti air hujan / efek daun talas pada kaca mobil untuk pandangan jernih saat hujan.',
                'is_active'   => true,
            ],
            [
                'name'        => 'Poles Bodi & Claying Sterilisasi Cat Dasar',
                'category'    => 'mobil',
                'price'       => 300000,
                'description' => 'Pembersihan jamur cat dan partikel kasar sebelum pemasangan agar stiker menempel sempurna tanpa bintik.',
                'is_active'   => true,
            ],
            [
                'name'        => 'Protection Sealant Spray Gloss/Matte Boost',
                'category'    => 'all',
                'price'       => 150000,
                'description' => 'Cairan proteksi UV khusus permukaan stiker vinyl untuk menjaga ketajaman warna tidak cepat pudar.',
                'is_active'   => true,
            ],
            [
                'name'        => 'Pasang Kaca Film Anti-Gores Speedometer & Spidometer',
                'category'    => 'motor',
                'price'       => 35000,
                'description' => 'PPF transparan pelindung mika spidometer motor dari goresan kunci atau sinar matahari terik.',
                'is_active'   => true,
            ],
        ];

        foreach ($addons as $addon) {
            Addon::firstOrCreate(
                ['name' => $addon['name']],
                $addon
            );
        }
    }
}
