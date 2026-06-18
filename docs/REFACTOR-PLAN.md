# Refactor: Dynamic CMS → Static Config

## 📋 Daftar Perubahan File

Dokumen ini mencatat **semua file yang berubah** saat refactor dari dynamic CMS ke static config.

---

## ✅ File yang Sudah Dibuat/Diubah

### 1. `config/company_cms.php` ✅ BARU

**Lokasi:** `c:\laragon\www\informasi_pemesanan_wrapping\config\company_cms.php`

**Tujuan:** 
- Menyimpan semua default/static CMS content
- Sumber tunggal untuk UI text, labels, hero titles, dll
- Mudah dimodifikasi tanpa perlu akses database

**Isi:**
- `home` - Homepage content (title, subtitle, features, steps)
- `about` - About page content
- `services` - Services page content
- `catalog` - Catalog page content
- `gallery` - Gallery page content
- `orders` - Order page labels
- `cart` - Shopping cart labels
- `checkout` - Checkout process labels
- `dashboard` - Dashboard labels dan content
- `layout` - Layout preferences (colors, dark mode, grid columns)
- `footer` - Footer copyright dan auth badge

**Cara Pakai di Code:**
```php
// Di controller
$homeTitle = companyCms('home.title');

// Di blade view
{{ companyCms('home.title') }}

// Dengan default fallback
{{ companyCms('home.title', 'Default Title') }}
```

---

### 2. `app/Helpers/CompanyCmsHelper.php` ✅ BARU

**Lokasi:** `c:\laragon\www\informasi_pemesanan_wrapping\app\Helpers\CompanyCmsHelper.php`

**Tujuan:**
- Menyediakan 3 helper function untuk membaca CMS content
- Support fallback ke database untuk transisi bertahap

**Helper Functions:**

#### `companyCms($key, $default = null, $fallbackToDb = true)`
Fungsi utama untuk membaca dari config dengan optional DB fallback.
```php
companyCms('home.title')                 // Baca dari config
companyCms('home.title', 'My Title')     // Dengan default
companyCms('home.title', null, false)    // Tanpa DB fallback
```

#### `companyCmsFromDb($column, $default = null)`
Helper untuk fallback ke database (untuk transisi).
```php
companyCmsFromDb('home_title', 'Default')
```

#### `companyCmsForAPI($section = null)`
Untuk API responses - return CMS data dalam format array.
```php
companyCmsForAPI('home')  // Return seluruh home section
companyCmsForAPI()        // Return semua CMS config
```

---

### 3. `composer.json` ✅ DIUBAH

**Lokasi:** `c:\laragon\www\informasi_pemesanan_wrapping\composer.json`

**Perubahan:**
```diff
  "autoload": {
      "files": [
          "app/helpers.php",
+         "app/Helpers/CompanyCmsHelper.php"
      ],
```

**Tujuan:** Mendaftarkan helper file agar auto-loaded oleh Laravel.

---

## 📝 File yang Perlu Diubah SELANJUTNYA

### A. Model: `app/Models/ProfilPerusahaan.php`

**Status:** ⏳ PENDING - Akan diubah di tahap berikutnya

**Perubahan yang akan dilakukan:**
1. Hapus `fillable` kolom yang sekarang statis (tidak perlu)
2. Tambah method accessor untuk backward compatibility
3. Update casts jika diperlukan

**Contoh:**
```php
// SEBELUM (Dynamic)
protected $fillable = [
    'home_title',
    'home_subtitle',
    'home_feature_title',
    // ... 100+ kolom lainnya
];

// SESUDAH (Ringan)
protected $fillable = [
    'nama_perusahaan',
    'deskripsi',
    'alamat',
    'email',
    // ... hanya data operasional
];

// Accessor untuk backward compatibility
public function getHomeTitleAttribute()
{
    return companyCms('home.title', $this->attributes['home_title'] ?? null);
}
```

---

### B. Views/Blade: Semua file di `resources/views/`

**Status:** ⏳ PENDING - Akan diubah sesuai kebutuhan

**Perubahan yang akan dilakukan:**
Ganti akses kolom dengan helper function.

**Contoh:**
```diff
- <!-- SEBELUM (Baca dari model) -->
- <h1>{{ $profil->home_title }}</h1>
- <p>{{ $profil->home_subtitle }}</p>

+ <!-- SESUDAH (Baca dari config) -->
+ <h1>{{ companyCms('home.title') }}</h1>
+ <p>{{ companyCms('home.subtitle') }}</p>
```

---

### C. Controllers: Semua file di `app/Http/Controllers/`

**Status:** ⏳ PENDING - Akan diubah sesuai kebutuhan

**Perubahan yang akan dilakukan:**
Ganti pengaksesan langsung ke column dengan helper.

**Contoh:**
```diff
- // SEBELUM
- $data['homeTitle'] = $profil->home_title;
- $data['homeSubtitle'] = $profil->home_subtitle;

+ // SESUDAH
+ $data['homeTitle'] = companyCms('home.title');
+ $data['homeSubtitle'] = companyCms('home.subtitle');
```

---

### D. API Resources: `app/Http/Resources/`

**Status:** ⏳ PENDING

**Perubahan:**
```diff
- public function toArray(Request $request): array
- {
-     return [
-         'home_title' => $this->home_title,
-         'home_subtitle' => $this->home_subtitle,
-     ];
- }

+ public function toArray(Request $request): array
+ {
+     return [
+         'home_title' => companyCms('home.title'),
+         'home_subtitle' => companyCms('home.subtitle'),
+     ];
+ }
```

---

### E. Filament Resources: `app/Filament/Resources/`

**Status:** ⏳ PENDING

**Perubahan yang akan dilakukan:**
1. Tandai kolom statis sebagai **read-only** atau hapus dari form
2. Atau buat form khusus untuk edit config file (opsional)

**Contoh (read-only):**
```php
TextInput::make('home_title')
    ->label('Home Title')
    ->disabled()  // ← Read-only
    ->default(companyCms('home.title'))
```

---

## 🗑️ Migration untuk Drop Column (OPTIONAL)

**Status:** ⏳ PENDING - Setelah semua code diupdate dan tested

**File:** `database/migrations/2026_06_17_xxxxxx_drop_cms_columns_from_profil_perusahaans.php`

Akan dibuat HANYA JIKA:
- Semua code sudah diupdate untuk membaca dari config
- Testing lokal sempurna
- Backup DB sudah dibuat
- Siap deploy ke production

**Kolom yang akan di-drop (contoh):**
```
home_title, home_subtitle, home_feature_title, home_feature_subtitle,
login_title, login_subtitle, login_image,
register_title, register_subtitle, register_image,
about_hero_title, about_hero_subtitle,
katalog_hero_title, katalog_hero_desc, katalog_intro_text,
... dan ~140+ kolom CMS lainnya
```

---

## 📊 Dampak Perubahan

### Database
- ✅ Tabel `profil_perusahaans` akan lebih ringkas (kurang ~140 kolom)
- ✅ Query lebih cepat
- ✅ Schema lebih mudah dipahami

### Application
- ✅ Code lebih clean (tidak banyak `$profil->home_title`)
- ✅ CMS content centralized di satu file config
- ✅ Mudah version control (config file di git)
- ✅ Mudah untuk environment spesifik (dev, staging, prod)

### Admin/Filament
- ⚠️ Field-field statis tidak bisa diubah dari Filament lagi
- ✅ Alternatif: buat text-editor untuk edit file config langsung (optional)
- ✅ Atau: tetap di DB tapi menjadi nullable + fallback ke config

---

## 🔄 Workflow Implementasi

### Phase 1: Setup (Sudah done ✅)
1. ✅ Buat `config/company_cms.php` dengan all default values
2. ✅ Buat helper functions di `CompanyCmsHelper.php`
3. ✅ Update `composer.json` untuk autoload

### Phase 2: Update Code (NEXT)
1. ⏳ Update ProfilPerusahaan model
2. ⏳ Update views untuk pakai helper
3. ⏳ Update controllers
4. ⏳ Update API resources
5. ⏳ Test lokal

### Phase 3: Cleanup (LAST)
1. ⏳ Update/remove Filament admin forms
2. ⏳ Create migration to drop columns (optional)
3. ⏳ Commit semua perubahan
4. ⏳ Deploy ke staging/production

---

## 📌 Catatan Penting

1. **Fallback Mechanism:** Saat ini helper masih bisa fallback ke DB, jadi Anda bisa update code secara bertahap tanpa break aplikasi.

2. **Database Migration:** Jangan hapus kolom sampai Anda yakin 100% semua code sudah menggunakan config.

3. **Backup:** Sebelum drop kolom, backup dulu nilai-nilainya ke config.

4. **Git:** Commit `config/company_cms.php` ke repo agar semua dev punya copy.

5. **Environment-specific:** Jika butuh per-environment, bisa buat `config/company_cms_production.php`, dsb.

---

## ✨ Keuntungan Setelah Refactor

| Aspek | Sebelum | Sesudah |
|-------|--------|--------|
| Kolom di DB | ~150 | ~30-40 |
| Jenis kolom | Campuran (CMS + operasional) | Clean (hanya operasional) |
| CMS Editing | Via Filament | Via config file atau custom page |
| Version Control | DB dependent | File-based (mudah track) |
| Query Performance | Slower | Faster |
| Maintenance | Complex | Simple |
| Scalability | Limited | Better |

---

_Last updated: 2026-06-17_
