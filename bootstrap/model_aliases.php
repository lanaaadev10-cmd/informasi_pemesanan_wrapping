<?php

/**
 * Registrasi alias transparan untuk model aplikasi.
 * Menjamin kompatibilitas mundur (backward-compatibility) 100%
 * tanpa memerlukan file wrapper fisik duplikat di folder app/Models.
 */

$modelAliases = [
    'App\Models\Booking' => \App\Models\Reservasi::class,
    'App\Models\Addon' => \App\Models\Tambahan::class,
    'App\Models\BookingAddon' => \App\Models\TambahanReservasi::class,
    'App\Models\BookingPayment' => \App\Models\PembayaranReservasi::class,
    'App\Models\BlockedDate' => \App\Models\TanggalDiblokir::class,
    'App\Models\VehicleBrand' => \App\Models\MerekKendaraan::class,
    'App\Models\VehicleModel' => \App\Models\ModelKendaraan::class,
    'App\Models\Rating' => \App\Models\Penilaian::class,
    'App\Models\RatingMedia' => \App\Models\MediaPenilaian::class,
    'App\Models\FormPesanan' => \App\Models\FormulirPesanan::class,
    'App\Models\BookingProgressPhoto' => \App\Models\FotoProgresReservasi::class,
    'App\Models\EWarranty' => \App\Models\GaransiElektronik::class,
    'App\Models\Pelanggan' => \App\Models\User::class,
    'App\Models\Pengguna' => \App\Models\User::class,
    'App\Models\DummyModel' => \App\Models\ModelVirtual::class,
];

// Prepend autoloader agar dieksekusi sebelum autoloader Composer
spl_autoload_register(function (string $class) use ($modelAliases) {
    if (isset($modelAliases[$class])) {
        class_alias($modelAliases[$class], $class);
    }
}, true, true);

// Daftarkan langsung alias jika target model sudah termuat
foreach ($modelAliases as $alias => $target) {
    if (class_exists($target, false) && !class_exists($alias, false)) {
        class_alias($target, $alias);
    }
}
