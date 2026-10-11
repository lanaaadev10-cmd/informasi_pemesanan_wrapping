<?php

namespace Database\Seeders;

use App\Models\VehicleBrand;
use App\Models\VehicleModel;
use Illuminate\Database\Seeder;

class VehicleSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            // ── MOTOR ──
            [
                'name'         => 'Honda (Motor)',
                'vehicle_type' => 'motor',
                'models'       => [
                    ['name' => 'Beat / Genio', 'vehicle_size' => 'small'],
                    ['name' => 'Scoopy', 'vehicle_size' => 'small'],
                    ['name' => 'Vario 125 / 160', 'vehicle_size' => 'medium'],
                    ['name' => 'PCX 150 / 160', 'vehicle_size' => 'medium'],
                    ['name' => 'ADV 150 / 160', 'vehicle_size' => 'medium'],
                    ['name' => 'CBR 150R / 250RR', 'vehicle_size' => 'large'],
                    ['name' => 'Forza 250', 'vehicle_size' => 'large'],
                ],
            ],
            [
                'name'         => 'Yamaha (Motor)',
                'vehicle_type' => 'motor',
                'models'       => [
                    ['name' => 'Mio / Fazzio / Grand Filano', 'vehicle_size' => 'small'],
                    ['name' => 'Aerox 155', 'vehicle_size' => 'medium'],
                    ['name' => 'NMAX 155', 'vehicle_size' => 'medium'],
                    ['name' => 'Lexi 125 / 155', 'vehicle_size' => 'medium'],
                    ['name' => 'R15 / R25 / MT-25', 'vehicle_size' => 'large'],
                    ['name' => 'XMAX 250', 'vehicle_size' => 'large'],
                ],
            ],
            [
                'name'         => 'Kawasaki (Motor)',
                'vehicle_type' => 'motor',
                'models'       => [
                    ['name' => 'KLX 150 / D-Tracker', 'vehicle_size' => 'medium'],
                    ['name' => 'Ninja 250 Fi / ZX-25R', 'vehicle_size' => 'large'],
                    ['name' => 'W175', 'vehicle_size' => 'medium'],
                ],
            ],
            [
                'name'         => 'Vespa (Motor)',
                'vehicle_type' => 'motor',
                'models'       => [
                    ['name' => 'Vespa LX / S 125', 'vehicle_size' => 'small'],
                    ['name' => 'Vespa Primavera / Sprint 150', 'vehicle_size' => 'medium'],
                    ['name' => 'Vespa GTS 150 / 300', 'vehicle_size' => 'large'],
                ],
            ],

            // ── MOBIL ──
            [
                'name'         => 'Toyota',
                'vehicle_type' => 'mobil',
                'models'       => [
                    ['name' => 'Agya / Yaris', 'vehicle_size' => 'small'],
                    ['name' => 'Raize', 'vehicle_size' => 'small'],
                    ['name' => 'Avanza / Veloz', 'vehicle_size' => 'medium'],
                    ['name' => 'Rush / Yaris Cross', 'vehicle_size' => 'medium'],
                    ['name' => 'Corolla Altis / Camry', 'vehicle_size' => 'medium'],
                    ['name' => 'Innova Reborn / Zenix', 'vehicle_size' => 'large'],
                    ['name' => 'Fortuner', 'vehicle_size' => 'large'],
                    ['name' => 'Alphard / Vellfire', 'vehicle_size' => 'xlarge'],
                    ['name' => 'HiAce Premio / Commuter', 'vehicle_size' => 'xlarge'],
                ],
            ],
            [
                'name'         => 'Honda (Mobil)',
                'vehicle_type' => 'mobil',
                'models'       => [
                    ['name' => 'Brio Satya / RS', 'vehicle_size' => 'small'],
                    ['name' => 'Jazz / City Hatchback', 'vehicle_size' => 'small'],
                    ['name' => 'WR-V', 'vehicle_size' => 'small'],
                    ['name' => 'HR-V / BR-V', 'vehicle_size' => 'medium'],
                    ['name' => 'Civic Sedan / Turbo', 'vehicle_size' => 'medium'],
                    ['name' => 'CR-V Turbo / Hybrid', 'vehicle_size' => 'large'],
                    ['name' => 'Accord', 'vehicle_size' => 'large'],
                ],
            ],
            [
                'name'         => 'Mitsubishi',
                'vehicle_type' => 'mobil',
                'models'       => [
                    ['name' => 'Mirage', 'vehicle_size' => 'small'],
                    ['name' => 'Xpander / Xpander Cross', 'vehicle_size' => 'medium'],
                    ['name' => 'Xforce', 'vehicle_size' => 'medium'],
                    ['name' => 'Pajero Sport', 'vehicle_size' => 'large'],
                    ['name' => 'Triton Double Cabin', 'vehicle_size' => 'xlarge'],
                ],
            ],
            [
                'name'         => 'Suzuki',
                'vehicle_type' => 'mobil',
                'models'       => [
                    ['name' => 'Ignis / S-Presso / Baleno', 'vehicle_size' => 'small'],
                    ['name' => 'Ertiga / XL7', 'vehicle_size' => 'medium'],
                    ['name' => 'Grand Vitara / Jimny', 'vehicle_size' => 'medium'],
                ],
            ],
            [
                'name'         => 'Daihatsu',
                'vehicle_type' => 'mobil',
                'models'       => [
                    ['name' => 'Ayla / Sirion', 'vehicle_size' => 'small'],
                    ['name' => 'Rocky', 'vehicle_size' => 'small'],
                    ['name' => 'Xenia / Terios', 'vehicle_size' => 'medium'],
                    ['name' => 'Gran Max Minibus / Blind Van', 'vehicle_size' => 'large'],
                ],
            ],
            [
                'name'         => 'Hyundai',
                'vehicle_type' => 'mobil',
                'models'       => [
                    ['name' => 'Creta', 'vehicle_size' => 'small'],
                    ['name' => 'Stargazer / Stargazer X', 'vehicle_size' => 'medium'],
                    ['name' => 'Ioniq 5 / Ioniq 6', 'vehicle_size' => 'medium'],
                    ['name' => 'Santa Fe / Palisade', 'vehicle_size' => 'large'],
                    ['name' => 'Staria', 'vehicle_size' => 'xlarge'],
                ],
            ],
        ];

        foreach ($brands as $bData) {
            $brand = VehicleBrand::firstOrCreate(
                ['name' => $bData['name']],
                [
                    'vehicle_type' => $bData['vehicle_type'],
                    'is_active'    => true,
                ]
            );

            foreach ($bData['models'] as $m) {
                VehicleModel::firstOrCreate(
                    [
                        'brand_id' => $brand->id,
                        'name'     => $m['name'],
                    ],
                    [
                        'vehicle_size' => $m['vehicle_size'],
                        'is_active'    => true,
                    ]
                );
            }
        }
    }
}
