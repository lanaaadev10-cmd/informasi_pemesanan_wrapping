Perbaikan Error kemarin

1. Buat ulang .env yang ternyata hilang Note: gunakan database sebelumnya tanpa menggunakan perintah php artisan migrate, cuku gunakan php artisan key:generate. terakhir jalankan php artisan optimize:clear.
2. silahkan cek kembali apakah sudah aman? jika belum lanjut baca lagi bro

3. Hapus kode pada file bootstrap/app.php - Hapus middleware ShareSettingToViews pada baris 25-27, berikut kodenya: $middleware->web(append: [
    //\App\Http\Middleware\ShareSettingsToViews::class,
]);

cukup komen saja apabila nantinya diperlukan

4. Hapus atau komen saja pada file routes/web.php - hapus atau komen importnya use App\Http\Controllers\Admin\OfflineOrderController;

lanjut hapus atau komen saja pada baris 76-83, berikut kelengkapan kodenya: Route::middleware('role:admin')->prefix('admin-offline')->name('admin.offline.')->group(function () {
            Route::get('/orders', [OfflineOrderController::class, 'index'])->name('orders.index');
                 Route::get('/orders/create', [OfflineOrderController::class, 'create'])->name('orders.create');
                 Route::post('/orders', [OfflineOrderController::class, 'store'])->name('orders.store');
                 Route::get('/orders/{id}/edit', [OfflineOrderController::class, 'edit'])->name('orders.edit');
                 Route::put('/orders/{id}', [OfflineOrderController::class, 'update'])->name('orders.update');
                 Route::delete('/orders/{id}', [OfflineOrderController::class, 'destroy'])->name('orders.destroy');
             });

Note: Saran saya untuk code yang berubah cukup komen saja dan tidak perlu menghapusnya, dikarenakan jika di kemudian hari akan dipakai tinggal hapus komennya saja, itu aja sih dari gua cmiwiwww

---

## Perbaikan Sesi 2 (6 Juli 2026)

### 1. Memperbaiki setting repository di config/settings.php
**Masalah:** File `config/settings.php` bagian baris 38 menggunakan kelas `App\Settings\Repositories\CachedDatabaseSettingsRepository` yang ternyata tidak ada di dalam project.
**Perbaikan:** Ganti dengan kelas bawaan dari vendor yaitu `Spatie\LaravelSettings\SettingsRepositories\DatabaseSettingsRepository`.

### 2. Memperbaiki format data fitur di tabel layanans
**Masalah:** Data fitur untuk layanan "Custom 2" (id=4) formatnya salah. Awalnya ditulis sebagai array yang berisi object: `[{"nama_fitur": "..."}]`, padahal yang benar adalah array biasa berisi teks: `["...", "..."]`.
**Perbaikan:** Ubah langsung di database, kolom `fitur` record id=4, dari format object jadi format teks biasa.

### 3. Memperbaiki route katalog
**Masalah:** Di file `routes/web.php` baris 44, route `katalog.user` masih mengarah ke fungsi `DashboardController::layanan` yang sudah tidak ada.
**Perbaikan:** Ganti alamatnya jadi `CustomerController::katalog`.

### 4. Menambah tanda tanya (nullsafe) di file blade
**Masalah:** Beberapa file blade (tampilan website) ngambil data dari relasi database yang kadang kosong (null), sehingga muncul error "Call to member function on null".
**Perbaikan:** Tambah tanda `?->` di 4 tempat di file `_order-card.blade.php` dan 2 tempat di file `_recent-activity.blade.php`. Jadi kalau datanya kosong, ya dilewati aja tanpa error.

### 5. Menambah properti yang hilang di settings
**Masalah:** Beberapa file settings punya properti yang dipakai di tampilan website tapi belum didaftarkan di kelasnya.
**Perbaikan:** Tambah properti berikut:
- `DashboardCustomerSettings.php` — tambah `dashboard_title` dan `dashboard_subtitle`
- `HomepageSettings.php` — tambah `home_title`, `home_hero_image`, `home_feature_title`, `home_feature_subtitle`
- `ContentSettings.php` — tambah `nav_whatsapp` dan `label_temukan_kami`

### 6. Memperbaiki fungsi hapus di KeranjangController
**Masalah:** Di `KeranjangController.php` baris 95, pas mau hapus item keranjang, pake perintah `$this->authorize()` yang ternyata tidak bisa dipakai karena kelas Controller di project ini tidak punya fitur authorize.
**Perbaikan:** Ganti dengan pengecekan manual: dicek dulu apakah pemilik keranjangnya sama dengan user yang login. Kalau beda, ditolak.

### 7. Mengubah cara hapus cache settings
**Masalah:** Pas admin menyimpan data Profil Perusahaan, data baru kadang tidak langsung muncul di website karena tersimpan di cache selama 24 jam.
**Percobaan 1:** Pake perintah `Artisan::call('settings:clear-cache')` — gagal karena perintah itu cuma bisa dijalankan lewat terminal, bukan dari dalam kode website.
**Percobaan 2:** Ganti pake `SettingsCacheFactory` — secara teori bisa, tapi hasilnya tetap sama (tidak ngefek).
**Kesimpulan:** Akhirnya fitur hapus cache ini dicabut aja dulu.

### 8. Menghapus menu Profil Perusahaan dari sidebar admin
**Masalah:** Menu Profil Perusahaan di halaman admin ternyata bermasalah dan tidak bisa dipakai dengan baik.
**Perbaikan:** Sembunyikan menu "Profil Perusahaan" dari sidebar admin dengan cara menambah `shouldRegisterNavigation = false` di file `CompanyResource.php`.
**Error tambahan:** Awalnya pake tipe data `?bool` (nullable) padahal di Filament tipe datanya `bool` biasa, jadi muncul error PHP. Sudah dibenerin jadi `bool` aja.

---

## Perbaikan Sesi 3 (6 Juli 2026) — Form New Pesanan Admin

### Masalah
Halaman **New Pesanan** di admin (Transaksi → Kelola Pesanan → tombol New Pesanan) tidak bisa diisi. Semua field di bagian "Informasi Pesanan" dalam keadaan disabled (abu-abu) dan kosong. Admin cuma bisa melihat form kosong tanpa bisa ngisi apa-apa.

### Perbaikan

**File 1: `app/Models/Pesanan.php`**
- Tambah `whatsapp_number` ke daftar `$fillable` biar bisa disimpan ke database. Kolom ini sebenarnya sudah ada di tabel, cuma belum dipake aja.

**File 2: `app/Filament/Resources/Pesanans/Pesanans/Schemas/PesananForm.php`**

Di bagian **"Informasi Pesanan"**, perbaikannya:
- **ID Pesanan (`kode_pesanan`)** — sekarang otomatis terisi kode unik (contoh: `PSN-A3B9X7K2`), tetap terkunci karena diisi sistem.
- **Pelanggan (`id_user`)** — dari yang tadinya abu-abu, sekarang bisa dicari dan dipilih (searchable select), baik New maupun Ubah.
- **Tanggal Pesan (`tanggal_pesan`)** — otomatis terisi tanggal hari ini. Bisa diganti kapan saja, baik New maupun Ubah.
- **Total Harga (`total_harga`)** — sekarang bisa diisi manual, baik New maupun Ubah.
- **Nomor Telepon Pelanggan (`whatsapp_number`)** — field baru buat input nomor HP pelanggan, biar admin punya kontak langsung.

Di bagian **"Status & Kendali"**:
- **Status** — otomatis terpilih "Menunggu Verifikasi Pesanan" pas buat baru, jadi admin gak perlu milih manual.

### Catatan Perbaikan Tambahan
Awalnya field `id_user`, `tanggal_pesan`, dan `total_harga` dikasih kondisi `disabled` saat halaman **Ubah (Edit)**. Ternyata admin tetap perlu mengedit data tersebut. Jadi kondisi disabled untuk ketiga field itu dihapus. Sekarang **semua field** (kecuali `kode_pesanan`) bisa diedit di halaman New maupun Ubah.

---

## Perbaikan Sesi 4 (19 Juli 2026) — Refactor Besar-Besaran

### 1. Foto Galeri — Sekarang Muncul
**Masalah:** Gambar galeri tidak muncul karena file-nya (jpg) tidak ada di folder `public/images/galeri/`. Folder dan file-nya memang tidak pernah dibuat.
**Perbaikan:** Semua foto galeri sekarang menggunakan gambar dari Unsplash (situs gambar gratis). Gambar akan muncul tanpa perlu menyimpan file di server. Ada tabel baru di `StaticContent.php` yang berisi pasangan "path lama → URL Unsplash", dan fungsi `galeriFoto()` untuk menerjemahkannya.

### 2. `StaticContent` Sekarang Berfungsi Beneran
**Masalah:** File `StaticContent.php` ada dan berisi konstanta teks (nama perusahaan, judul, tombol, dll), tapi tidak ada satu pun tampilan website yang menggunkannya. Semua teks ditulis langsung (hardcode) di file Blade.
**Perbaikan:** Sekarang semua teks di halaman website ngacu ke `StaticContent`. Jadi kalau mau ganti teks, cukup edit satu file `app/Helpers/StaticContent.php`, refresh halaman, langsung berubah.

**Halaman yang sudah pakai StaticContent:**
- Navbar & Footer — nama brand, menu, tautan sosial, copyright
- Halaman Beranda — hero, keunggulan, portofolio, CTA
- Halaman Profil Perusahaan — nama perusahaan, deskripsi, visi misi
- Halaman Layanan — badge, judul, deskripsi
- Halaman Tentang Kami — judul hero, visi, misi, tim, CTA
- Halaman Galeri — judul, deskripsi, filter

### 3. Galeri — Pindah ke Database
**Masalah:** Data galeri (foto, judul, deskripsi) ditulis manual di file Blade dan `StaticContent.php`. Kalau mau nambah/ubah, harus edit kode.
**Perbaikan:** Data galeri sekarang disimpan di tabel `galeris` di database. Ada seeder (`GaleriSeeder`) yang isi 3 data real: Porsche 911 GT3, Mercedes-Benz S-Class, dan Lamborghini Urus.

**Cara nambah/ubah galeri:** Login admin → menu Galeri → Tambah/Edit. Hasil langsung muncul di website, di halaman galeri maupun di halaman beranda bagian portofolio.

### 4. Layanan — Pindah ke Database
**Masalah:** Sama seperti galeri, data layanan awalnya ditulis manual di file Blade (4 paket: Stealth Matte, Mirror Glossy, Satin Silk, Paint Protection).
**Perbaikan:** Data layanan sekarang disimpan di tabel `layanans` di database. Ada seeder (`LayananSeeder`) yang isi 3 data real sesuai permintaan:

| Layanan | Tipe | Harga |
|---------|------|-------|
| Variasi Mobil | custom | Menyesuaikan |
| Kaca Film | custom | Menyesuaikan |
| Audio Mobil | custom | Menyesuaikan |

**Cara nambah/ubah layanan:** Login admin → menu Layanan → Tambah/Edit.

### 5. Galeri di Dashboard Customer
**Masalah:** Data galeri sudah dikirim ke halaman dashboard customer tapi tidak ditampilkan.
**Perbaikan:** Sekarang ada bagian "Galeri Portofolio" di dashboard customer (setelah login), menampilkan foto-foto galeri dalam grid. Sinkron dengan data di database.

### 6. Filter Kategori Galeri — Sekarang Berfungsi
**Masalah:** Tombol filter kategori di halaman galeri mengarah ke method `kategori()` yang tidak ada, jadinya error 500.
**Perbaikan:** Method `kategori()` sudah ditambahkan. Klik kategori → tampilkan foto sesuai kategori. Filternya juga otomatis menyesuaikan kategori yang ada di database.

### 7. API Galeri — Berfungsi
**Masalah:** Endpoint `/api/galeri/*` error karena file `GaleriApiController.php` tidak ada.
**Perbaikan:** File sudah dibuat, isinya ngambil data dari database.

### 8. Migrasi Database — Beres Total
**Masalah:** Ada 2 error sebelumya:
- Foreign key `keranjangs.id_paket` mengacu ke tabel `pakets` yang tidak ada (seharusnya ke `layanans`)
- Ada duplikasi unique index di file migrasi
**Perbaikan:** Dua-duanya sudah dibenerin. Sekarang `php artisan migrate:fresh --seed` jalan 100% mulus.

### Cara Gampang Mengelola Website Sekarang

| Yang Mau Dilakukan | Caranya |
|-------------------|---------|
| Ganti teks (nama toko, judul, tombol, dll) | Edit file `app/Helpers/StaticContent.php` |
| Tambah/Ubah/Hapus galeri | Login admin → menu Galeri |
| Tambah/Ubah/Hapus layanan | Login admin → menu Layanan |
| Ganti foto galeri | Login admin → klik galeri → upload foto baru |
| Reset data dari awal | Jalankan: `php artisan migrate:fresh --seed` |

# Catatan Perbaikan Bug Pada Tanggal 21 Juli 2026

## Bug 1: Gambar layanan di dashboard customer tidak terdefinisi

**Penyebab:** Data seed (`LayananSeeder.php`) tidak mengisi kolom `foto_contoh`, sehingga semua layanan memiliki `foto_contoh = null`. Dashboard hanya menampilkan icon placeholder.

**Solusi (manual):** Admin perlu upload foto layanan via Filament admin panel agar `foto_contoh` terisi.

---

## Bug 2: Gambar Katalog Layanan berbeda dengan Halaman Layanan

**Penyebab:** Kedua halaman menggunakan set fallback image (Unsplash URL) yang berbeda ketika `foto_contoh = null`.

**Perbaikan:**

1. **`app/Helpers/StaticContent.php`** — Menambahkan konstanta `LAYANAN_FALLBACK_IMAGES` yang berisi 4 URL fallback yang seragam.

2. **`resources/views/landing/layanan/index.blade.php`** — Mengganti inline array fallback dengan `StaticContent::LAYANAN_FALLBACK_IMAGES`.

3. **`resources/views/landing/katalog/_grid.blade.php`** — Mengganti 3 fallback berbeda (wide card, medium card, grid items) dengan `StaticContent::LAYANAN_FALLBACK_IMAGES` yang konsisten dengan halaman Layanan.

---

## Bug 3: Gambar galeri broken jika upload via Filament

**Penyebab:** Tiga file menggunakan `{{ $item->foto }}` tanpa prefix `asset('storage/')`. Data seeder menyimpan full URL (Unsplash) jadi aman, tapi upload Filament menyimpan relative path (`galeri/file.jpg`) sehingga broken.

**Perbaikan (3 file):**

1. **`resources/views/landing/beranda/_portofolio.blade.php`**
2. **`resources/views/landing/galeri/_grid.blade.php`**
3. **`resources/views/dashboard/customer/dashboard/_gallery-section.blade.php`**

   Semua diubah menjadi:
   ```php
   {{ str_starts_with($item->foto, 'http') ? $item->foto : asset('storage/' . $item->foto) }}
   ```

---

## Perbaikan Sesi 5 (11 Agustus 2026) — Satu Pemicu Update Foto Galeri & Layanan (Tanpa Reseed)

### Masalah
Update foto layanan dan galeri harusnya bisa dari satu tempat yang sama, tapi caranya beda:
- **Layanan:** gambar fallback dibaca saat render dari `StaticContent::LAYANAN_FALLBACK_IMAGES` — edit langsung berubah, tanpa reseed.
- **Galeri:** URL gambar di-resolve oleh seeder (`GaleriSeeder`) lalu disimpan permanen ke kolom `galeri.foto` — ganti URL wajib reseed (dan reseed `GaleriSeeder` menimbulkan duplikat kalau data lama belum dihapus).

Hasil keputusan: seragamkan pemicunya **bertipe layanan** = cukup edit `StaticContent.php`, tanpa reseed, tanpa cache. (Gambar per-layanan spesifik TIDAK dibutuhkan; fallback posisi tetap dipakai.)

### Perbaikan (2 file)

**File 1: `app/Helpers/StaticContent.php`**
Tambah helper baru `galeriFotoByJudul()`:
```php
public static function galeriFotoByJudul(string $judul, ?string $stored = null): string
{
    foreach (self::galeriItems() as $item) {
        if ($item['judul'] === $judul) {
            return self::galeriFoto($item['foto']);   // resolve key → URL dari $galeriFotoMap
        }
    }
    return $stored ?: self::$galeriFotoMap['images/galeri/tesla-model-s.jpg'] ?? '';
}
```

**File 2: `app/Models/Galeri.php`**
Tambah accessor supaya semua view ter-resolve otomatis tanpa diubah:
```php
public function getFotoAttribute($value)
{
    return \App\Helpers\StaticContent::galeriFotoByJudul($this->judul, $value);
}
```

### Cara kerja
- Galeri hasil seed (Porsche 911 GT3, Mercedes-Benz S-Class, Lamborghini Urus) punya judul stabil yang ada di `galeriItems()`. Gambar di-resolve dari `judul → galeriFoto(key) → URL di $galeriFotoMap` saat render, sehingga nilai lama di DB tidak lagi dipakai.
- Galeri lain (buatan admin via Filament, judul tidak dikenal) → helper mengembalikan nilai `foto` asli; view memproses seperti biasa (`http` → apa adanya, selain itu prefix `storage/`).

### Hasil
| Gambar | Edit di `StaticContent.php` | Perlu reseed? |
|--------|-----------------------------|---------------|
| Galeri | `$galeriFotoMap` (baris 154) | Tidak |
| Layanan | `LAYANAN_FALLBACK_IMAGES` (baris 245) | Tidak |

Tidak ada perubahan view, seeder, atau DB. File tersentuh hanya 2 (`StaticContent.php`, `Galeri.php`).
