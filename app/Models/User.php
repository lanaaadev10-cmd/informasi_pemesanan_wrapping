<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// Spatie
use Spatie\Permission\Traits\HasRoles;

// Filament
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable, HasRoles, HasApiTokens;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    // 🔒 Sembunyikan field sensitif dari JSON/array response
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // 🔒 Cast tipe data agar aman
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    // 🔐 Batasi akses ke Filament
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->hasRole('admin');
    }
}


