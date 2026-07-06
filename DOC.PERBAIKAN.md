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