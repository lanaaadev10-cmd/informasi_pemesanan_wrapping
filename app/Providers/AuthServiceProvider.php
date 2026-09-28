<?php

namespace App\Providers;

use App\Models\Keranjang;
use App\Models\Pesanan;
use App\Models\Rating;
use App\Models\User;
use App\Policies\KeranjangPolicy;
use App\Policies\PesananPolicy;
use App\Policies\RatingPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Pesanan::class => PesananPolicy::class,
        Keranjang::class => KeranjangPolicy::class,
        User::class => UserPolicy::class,
        Rating::class => RatingPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
