<?php

namespace App\Http\Controllers;

use App\Models\Layanan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Controller untuk Fitur Kalkulator Estimasi Biaya & Kebutuhan Bahan Wrapping (Fase 2).
 * Menyediakan perhitungan presisi ukuran bahan (meter), estimasi waktu, garansi,
 * dan biaya berbasis standar industri car wrapping dan PPF.
 */
class KalkulatorController extends Controller
{
    /**
     * Konfigurasi matriks ukuran kendaraan & multiplier industri.
     */
    protected const VEHICLE_METRICS = [
        'small' => [
            'label' => 'City Car / Hatchback (Kecil)',
            'examples' => 'Brio, Yaris, Jazz, Raize, Ignis',
            'multiplier' => 1.0,
            'meter_wrap' => 13,
            'meter_partial' => 3,
            'meter_chrome' => 2,
            'meter_ppf' => 14,
        ],
        'medium' => [
            'label' => 'Sedan / Compact SUV (Sedang)',
            'examples' => 'Civic, Corolla, HRV, Mazda 3, Creta',
            'multiplier' => 1.15,
            'meter_wrap' => 16,
            'meter_partial' => 4,
            'meter_chrome' => 2.5,
            'meter_ppf' => 17,
        ],
        'large' => [
            'label' => 'Large SUV / MPV (Besar)',
            'examples' => 'Fortuner, Pajero, Innova Zenix, CR-V, Palisade',
            'multiplier' => 1.35,
            'meter_wrap' => 19,
            'meter_partial' => 5,
            'meter_chrome' => 3,
            'meter_ppf' => 20,
        ],
        'extra_large' => [
            'label' => 'Luxury Van / Supercar (Extra Besar)',
            'examples' => 'Alphard, Land Cruiser, Porsche, BMW 7 Series',
            'multiplier' => 1.6,
            'meter_wrap' => 23,
            'meter_partial' => 6,
            'meter_chrome' => 3.5,
            'meter_ppf' => 24,
        ],
    ];

    /**
     * Konfigurasi cakupan pengerjaan & harga dasar.
     */
    protected const SCOPE_METRICS = [
        'full_wrap' => [
            'label' => 'Full Body Wrapping',
            'desc' => 'Membungkus 100% bodi eksterior kendaraan dengan proteksi cat total.',
            'base_price' => 4500000,
            'duration' => '2 - 3 Hari',
            'warranty' => '2 Tahun Garansi Resmi',
        ],
        'partial' => [
            'label' => 'Partial Wrap (Kap & Atap)',
            'desc' => 'Wrapping area tertentu seperti kap mesin, atap panoramic look, atau spion.',
            'base_price' => 1250000,
            'duration' => '1 Hari Kerja',
            'warranty' => '1 Tahun Garansi Resmi',
        ],
        'chrome_delete' => [
            'label' => 'Chrome Delete / Night Package',
            'desc' => 'Menghitamkan seluruh lis krom jendela, grill, dan ornamen bodi untuk tampilan agresif.',
            'base_price' => 950000,
            'duration' => '4 - 6 Jam',
            'warranty' => '1 Tahun Garansi Resmi',
        ],
        'full_ppf' => [
            'label' => 'Full Paint Protection Film (PPF)',
            'desc' => 'Lapisan film tebal bening termoplastik (TPU) dengan kemampuan self-healing terhadap kerikil & goresan.',
            'base_price' => 14000000,
            'duration' => '3 - 4 Hari',
            'warranty' => '5 Tahun Garansi Resmi',
        ],
    ];

    /**
     * Konfigurasi tipe bahan/finishing.
     */
    protected const FINISH_METRICS = [
        'glossy' => [
            'label' => 'Glossy High-Shine',
            'multiplier' => 1.0,
            'desc' => 'Kilau wet-look menyerupai cat oven pabrik original.',
        ],
        'satin_metallic' => [
            'label' => 'Satin Metallic / Silk',
            'multiplier' => 1.15,
            'desc' => 'Tekstur doff halus dengan pantulan cahaya metalik elegan.',
        ],
        'matte_stealth' => [
            'label' => 'Matte Stealth Black/Color',
            'multiplier' => 1.10,
            'desc' => 'Tampilan doff pekat bergaya supercar agresif.',
        ],
        'ultra_ppf' => [
            'label' => 'TPU Self-Healing Film (PPF)',
            'multiplier' => 1.0,
            'desc' => 'Material TPU kelas tertinggi tahan sengatan panas dan goresan.',
        ],
    ];

    /**
     * Tampilan utama halaman kalkulator wrapping.
     */
    public function index(Request $request)
    {
        $layanans = Layanan::all();

        $defaultCategory = $request->query('category', 'medium');
        $defaultScope    = $request->query('scope', 'full_wrap');
        $defaultFinish   = $request->query('finish', 'satin_metallic');

        $initialCalculation = $this->calculate(
            category: $defaultCategory,
            scope: $defaultScope,
            finish: $defaultFinish,
            layanans: $layanans
        );

        return view('landing.kalkulator.index', [
            'vehicleMetrics'     => self::VEHICLE_METRICS,
            'scopeMetrics'       => self::SCOPE_METRICS,
            'finishMetrics'      => self::FINISH_METRICS,
            'layanans'           => $layanans,
            'defaultCategory'    => $defaultCategory,
            'defaultScope'       => $defaultScope,
            'defaultFinish'      => $defaultFinish,
            'initialCalculation' => $initialCalculation,
        ]);
    }

    /**
     * Endpoint API / AJAX untuk kalkulasi real-time saat opsi diubah.
     */
    public function hitung(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'kategori_kendaraan' => 'required|string|in:small,medium,large,extra_large',
            'cakupan_layanan'    => 'required|string|in:full_wrap,partial,chrome_delete,full_ppf',
            'material_finish'    => 'required|string|in:glossy,satin_metallic,matte_stealth,ultra_ppf',
        ]);

        $layanans = Layanan::all();

        $calculation = $this->calculate(
            category: $validated['kategori_kendaraan'],
            scope: $validated['cakupan_layanan'],
            finish: $validated['material_finish'],
            layanans: $layanans
        );

        return response()->json([
            'status' => 'success',
            'data'   => $calculation,
        ]);
    }

    /**
     * Formula kalkulasi matematis standar industri.
     *
     * @return array<string, mixed>
     */
    protected function calculate(string $category, string $scope, string $finish, $layanans): array
    {
        $vConfig = self::VEHICLE_METRICS[$category] ?? self::VEHICLE_METRICS['medium'];
        $sConfig = self::SCOPE_METRICS[$scope] ?? self::SCOPE_METRICS['full_wrap'];
        $fConfig = self::FINISH_METRICS[$finish] ?? self::FINISH_METRICS['satin_metallic'];

        // Tentukan kebutuhan bahan (meter)
        $panjangMeter = match ($scope) {
            'partial'       => $vConfig['meter_partial'],
            'chrome_delete' => $vConfig['meter_chrome'],
            'full_ppf'      => $vConfig['meter_ppf'],
            default         => $vConfig['meter_wrap'],
        };

        // Hitung estimasi harga: Base Price * Vehicle Multiplier * Finish Multiplier
        $basePrice = $sConfig['base_price'];
        $vehicleMult = $vConfig['multiplier'];
        $finishMult = ($scope === 'full_ppf') ? 1.0 : $fConfig['multiplier'];

        $calculatedPrice = round(($basePrice * $vehicleMult * $finishMult) / 50000) * 50000;

        $minPrice = round(($calculatedPrice * 0.95) / 50000) * 50000;
        $maxPrice = round(($calculatedPrice * 1.05) / 50000) * 50000;

        // Cocokkan dengan layanan di database jika ada
        $matchedLayanan = $layanans->first(function ($item) use ($scope) {
            $name = strtolower($item->nama_layanan);
            return match ($scope) {
                'full_ppf'      => str_contains($name, 'ppf') || str_contains($name, 'protection'),
                'chrome_delete' => str_contains($name, 'chrome') || str_contains($name, 'variasi'),
                'partial'       => str_contains($name, 'partial') || str_contains($name, 'atap'),
                default         => str_contains($name, 'full') || str_contains($name, 'wrapping'),
            };
        }) ?? $layanans->first();

        // Bangun URL Booking dengan query pre-filled
        $notes = "Hasil Kalkulator: " . $vConfig['label'] . " (" . $sConfig['label'] . " - " . $fConfig['label'] . ", Estimasi " . $panjangMeter . "m)";
        $bookingParams = [
            'layanan_id'   => $matchedLayanan?->id_layanan,
            'vehicle_name' => $vConfig['label'],
            'notes'        => $notes,
        ];
        $bookingUrl = route('booking.create', array_filter($bookingParams));

        return [
            'category_key'             => $category,
            'category_label'           => $vConfig['label'],
            'vehicle_examples'         => $vConfig['examples'],
            'scope_key'                => $scope,
            'scope_label'              => $sConfig['label'],
            'scope_desc'               => $sConfig['desc'],
            'finish_key'               => $finish,
            'finish_label'             => $fConfig['label'],
            'panjang_bahan_meter'      => $panjangMeter,
            'panjang_bahan_formatted'  => $panjangMeter . ' Meter Roll',
            'estimasi_harga'           => $calculatedPrice,
            'estimasi_harga_formatted' => 'Rp ' . number_format($calculatedPrice, 0, ',', '.'),
            'rentang_harga_formatted'  => 'Rp ' . number_format($minPrice, 0, ',', '.') . ' - Rp ' . number_format($maxPrice, 0, ',', '.'),
            'estimasi_durasi'          => $sConfig['duration'],
            'garansi'                  => $sConfig['warranty'],
            'matched_layanan_id'       => $matchedLayanan?->id_layanan,
            'matched_layanan_nama'     => $matchedLayanan?->nama_layanan ?? 'Paket Layanan Rekomendasi',
            'booking_url'              => $bookingUrl,
            'features'                 => [
                'Free Cuci & Paint Decontamination sebelum pasang',
                'Pengerjaan di Ruang Tertutup Bebas Debu (Dust-Free Bay)',
                '14 Hari Garansi Free Re-Inspection & Edge Sealing',
                $sConfig['warranty'],
            ],
        ];
    }
}
