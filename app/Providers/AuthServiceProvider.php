<?php

namespace App\Providers;

use App\Models\Booking;
use App\Models\Keranjang;
use App\Models\Pengguna;
use App\Models\Penilaian;
use App\Models\Pesanan;
use App\Models\Rating;
use App\Models\Reservasi;
use App\Models\User;
use App\Policies\BookingPolicy;
use App\Policies\KeranjangPolicy;
use App\Policies\PenggunaPolicy;
use App\Policies\PenilaianPolicy;
use App\Policies\PesananPolicy;
use App\Policies\RatingPolicy;
use App\Policies\ReservasiPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Pesanan::class => PesananPolicy::class,
        Keranjang::class => KeranjangPolicy::class,
        User::class => UserPolicy::class,
        Pengguna::class => PenggunaPolicy::class,
        Rating::class => RatingPolicy::class,
        Penilaian::class => PenilaianPolicy::class,
        Booking::class => BookingPolicy::class,
        Reservasi::class => ReservasiPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
