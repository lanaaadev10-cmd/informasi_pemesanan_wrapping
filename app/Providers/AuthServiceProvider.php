<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\Pesanan;
use App\Models\Keranjang;
use App\Models\User;
use App\Policies\PesananPolicy;
use App\Policies\KeranjangPolicy;
use App\Policies\UserPolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Pesanan::class => PesananPolicy::class,
        Keranjang::class => KeranjangPolicy::class,
        User::class => UserPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
