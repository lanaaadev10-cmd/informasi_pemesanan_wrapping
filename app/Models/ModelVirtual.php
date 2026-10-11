<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model Virtual / Entitas Placeholder untuk Halaman Pengaturan Filament
 *
 * Filament mensyaratkan setiap `EditRecord` terikat pada turunan `Illuminate\Database\Eloquent\Model`.
 * Karena pengaturan profil perusahaan disimpan menggunakan package `spatie/laravel-settings` (bukan tabel Eloquent biasa),
 * `ModelVirtual` bertindak sebagai virtual record placeholder di `\App\Filament\Resources\Companies\Pages\EditCompany`.
 *
 * Properti:
 * - $table = 'dummy' : Nama tabel hipotetis (tidak pernah di-query ke database).
 * - $exists = false   : Mencegah operasi UPDATE SQL otomatis ke database.
 * - $timestamps = false: Menonaktifkan manajemen timestamp created_at / updated_at.
 */
class ModelVirtual extends Model
{
    protected $table = 'dummy';
    public $exists = false;
    public $timestamps = false;
    protected $guarded = [];
}

// Alias untuk kompatibilitas mundur
class_alias(ModelVirtual::class, 'App\Models\DummyModel');
