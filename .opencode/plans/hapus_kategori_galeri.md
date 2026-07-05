# Plan: Hapus Kolom Kategori dari Galeri

## 1. Model — `app/Models/Galeri.php:14-23`
Hapus `'kategori'` dari array `$fillable`.

**Sebelum:**
```php
protected $fillable = [
    'judul',
    'foto',
    'deskripsi',
    'tanggal_upload',
    'kategori',
    'sub_judul',
    'is_featured',
    'badge_text',
];
```

**Sesudah:**
```php
protected $fillable = [
    'judul',
    'foto',
    'deskripsi',
    'tanggal_upload',
    'sub_judul',
    'is_featured',
    'badge_text',
];
```

## 2. Form — `app/Filament/Resources/Galeris/Schemas/GaleriForm.php`
- Hapus `use Filament\Forms\Components\Select;` (line 10)
- Hapus block `Select::make('kategori')` — `. ->options(...) ->native(false)` (lines 49-57)
- Gabungkan `TextInput::make('badge_text')` langsung setelah `->columnSpanFull(),`

## 3. Table — `app/Filament/Resources/Galeris/Tables/GalerisTable.php`
Hapus seluruh block `TextColumn::make('kategori')` — lines 28-38.

## 4. Migration baru
Buat file `database/migrations/2026_07_02_000002_drop_kategori_from_galeris_table.php`:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('galeris', function (Blueprint $table) {
            $table->dropColumn('kategori');
        });
    }

    public function down(): void
    {
        Schema::table('galeris', function (Blueprint $table) {
            $table->string('kategori')->nullable();
        });
    }
};
```

## 5. Eksekusi
```bash
php artisan migrate
```

## Catatan
- Tidak perlu ubah view publik — galeri halaman public sudah hardcoded statis
- Tidak perlu ubah Select import di GaleriForm jika sudah dihapus
- Data existing di DB akan kehilangan kolom kategori (irreversible via down migration)
