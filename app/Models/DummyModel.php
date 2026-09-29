<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model Virtual / Entitas Dummy untuk Halaman Pengaturan Filament
 *
 * Filament v3 mensyaratkan setiap `EditRecord` terikat pada turunan `Illuminate\Database\Eloquent\Model`.
 * Karena pengaturan profil perusahaan disimpan menggunakan package `spatie/laravel-settings` (bukan tabel Eloquent biasa),
 * `DummyModel` bertindak sebagai virtual record placeholder di `\App\Filament\Resources\Companies\Pages\EditCompany`.
 *
 * Properti:
 * - $table = 'dummy' : Nama tabel hipotetis (tidak pernah di-query ke database).
 * - $exists = false   : Mencegah operasi UPDATE SQL otomatis ke database.
 * - $timestamps = false: Menonaktifkan manajemen timestamp created_at / updated_at.
 */
class DummyModel extends Model
{
    protected $table = 'dummy';
    public $exists = false;
    public $timestamps = false;
    protected $guarded = [];
}
