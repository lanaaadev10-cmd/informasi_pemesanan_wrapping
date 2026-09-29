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

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Model User (Akun Pengguna Sistem)
 *
 * @property int $id Primary key
 * @property string $name Nama lengkap user
 * @property string $email Alamat email user
 * @property \Carbon\Carbon|null $email_verified_at Tanggal verifikasi email
 * @property string $password Hash password
 * @property string|null $phone Nomor telepon / WhatsApp
 * @property string|null $no_hp Nomor handphone alternatif
 * @property bool $is_walk_in Menandakan akun dibuat otomatis untuk customer walk-in offline
 * @property int|null $walk_in_created_by ID admin yang membuat akun walk-in
 * @property string|null $walk_in_note Catatan khusus pelanggan walk-in
 * @property string|null $remember_token
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @property-read User|null $creator Admin yang membuat akun jika walk-in
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Booking> $bookings Daftar booking pengerjaan
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Pesanan> $pesanans Daftar pesanan wrapping
 */
class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable, HasRoles, HasApiTokens;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'is_walk_in',
        'walk_in_created_by',
        'walk_in_note',
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
        'is_walk_in' => 'boolean',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'walk_in_created_by', 'id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'user_id');
    }

    public function pesanans(): HasMany
    {
        return $this->hasMany(Pesanan::class, 'id_user');
    }

    // 🔐 Batasi akses ke Filament
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->hasRole('admin');
    }
}


