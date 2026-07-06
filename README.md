# Sistem Informasi dan Pemesanan Jasa Wrapping Kendaraan

## 1. Deskripsi Singkat Proyek

Kami sebagai Mahasiswa Politeknik Negeri Banyuwangi memiliki tugas PBL, disini kami dari memilih mitra yang bernama Bengkel Dantie Sticker, yang menyediakan berbaagai layanan modifikasi otomotif dan bengkel ini  berjarak sekitar 13km dari POLIWANGI, disini kami membantu mitra dalam menyelesaikan permasalahan mulai dari Informasi yang kurang diketahui pelanggan, layanan yang kurang jelas, pelacakan progres pengerjan yang masih manual hingga pencatatan bulanan yang masih manual. Kami membantu menyelesaikan permasalahan tersebut dengan cara membuat Sistem Informasi dan Jasa Pemesanan Wrapping berbasis Website, yang nantinya akan berguna untuk menanggulangi permasalahan yang ada.

**Pengguna utama aplikasi:**
1. **Customer (User)** — Pelanggan yang ingin memesan jasa wrapping.
2. **Admin** — Pemilik/staf yang mengelola layanan, pesanan, dan verifikasi pembayaran.
3. **Tamu (Guest)** — Pengunjung yang melihat halaman publik (beranda, galeri, layanan).

---

## 2. Fitur-Fitur yang Tersedia

### Customer

| Fitur | Keterangan |
|-------|-----------|
| **Registrasi & Login** | Mendaftar akun, login, logout (via Laravel Breeze + Sanctum) |
| **Verifikasi Email** | Email wajib diverifikasi untuk mengakses fitur proteksi |
| **Lihat Beranda** | Halaman depan dengan hero, keunggulan, portofolio, dan CTA |
| **Lihat Galeri** | Galeri statis hasil wrapping dengan filter kategori (matte, satin, glossy) |
| **Lihat Layanan** | Daftar paket layanan wrapping yang tersedia |
| **Lihat Katalog** | Katalog paket layanan dengan detail harga |
| **Lihat Profil Perusahaan** | Informasi perusahaan, visi, misi, dan tim |
| **Lihat Tentang Kami** | Halaman tentang perusahaan |
| **Tambah ke Keranjang** | Memilih paket layanan dan memasukkannya ke keranjang (maks. 3 paket) |
| **Kelola Keranjang** | Melihat, mengubah jumlah, menghapus item, dan mengosongkan keranjang |
| **Checkout / Buat Pesanan** | Mengisi data diri, alamat, data kendaraan, dan jadwal pengerjaan |
| **Upload Bukti Pembayaran** | Mengunggah bukti transfer setelah pesanan dikonfirmasi admin |
| **Lihat Daftar Pesanan** | Melihat semua pesanan dengan filter status (menunggu, berjalan, selesai) |
| **Lihat Detail Pesanan** | Detail satu pesanan termasuk status, items, form, dan pembayaran |
| **Lihat Invoice** | Invoice pesanan (hanya jika status sudah dikonfirmasi/diproses/selesai) |
| **Notifikasi In-App** | Notifikasi di dalam aplikasi (dashboard customer) |
| **Edit Profil** | Mengubah nama, email, dan password |
| **Hapus Akun** | Menghapus akun sendiri |

### Admin

| Fitur | Keterangan |
|-------|-----------|
| **Login Admin** | Login ke panel Filament di `/admin` |
| **Dashboard Admin** | Ringkasan statistik (total pesanan, pendapatan, dll.) — via API |
| **Kelola Layanan** | CRUD paket layanan (nama, deskripsi, harga, foto, fitur, kategori, estimasi waktu) |
| **Kelola Galeri** | CRUD galeri karya (judul, foto, deskripsi, sub_judul, is_featured, badge_text) |
| **Kelola Pesanan** | Melihat daftar pesanan, mengubah status (konfirmasi, verifikasi bayar, proses, selesai, tolak) |
| **Riwayat Transaksi** | Daftar pesanan yang sudah selesai/ditolak (read-only) |
| **Kelola User** | CRUD pengguna aplikasi |
| **Pengaturan Perusahaan** | Nama, alamat, telepon, email, logo, sosial media, WA link |
| **Notifikasi Database** | Notifikasi real-time via Filament database notifications |
| **Laporan** | Cetak laporan pendapatan harian/mingguan/bulanan |
| **API Admin** | Endpoint untuk dashboard stats, chart data, manajemen pesanan & verifikasi bayar |

### Fitur Tambahan

| Fitur | Keterangan |
|-------|-----------|
| **Autentikasi Sanctum** | API token-based authentication untuk mobile/SPA |
| **Role & Permission** | Dua role: `admin` dan `user` (Spatie Laravel Permission) |
| **Event & Listener** | 6 events + 7 listeners untuk alur notifikasi order lifecycle |
| **Queue Database** | Queue berbasis database untuk _background job_ |
| **Cache Database** | Cache berbasis database untuk settings dan data dashboard |
| **Upload Gambar** | Upload file ke `storage/app/public/` (bukti transfer, foto layanan, galeri) |
| **Throttle** | Rate limiting 60 request per 5 menit di route publik |
| **GET /logout** | Workaround untuk menghindari 419 Page Expired |
| **Metrics Endpoint** | Endpoint `/metrics` untuk monitoring (localhost only) |

---

## 3. Tech Stack / Framework

| Teknologi | Versi | Keterangan |
|-----------|-------|-----------|
| **PHP** | ^8.2 | Bahasa pemrograman |
| **Laravel** | ^12.0 | Framework PHP |
| **Filament** | ^5.0 | Admin panel UI (panel builder) |
| **MySQL** | ^8.4.3 | Database (default: SQLite via .env.example) |
| **Tailwind CSS** | ^3.1 | CSS framework |
| **Alpine.js** | ^3.4.2 | JavaScript library untuk interaktivitas frontend |
| **Vite** | ^6.0.11 | Build tool untuk asset frontend |
| **Laravel Breeze** | ^2.4 | Starter kit autentikasi (Blade) |
| **Laravel Sanctum** | ^4.3 | API token authentication |
| **Spatie Laravel Permission** | ^7.3 | Role & permission management |
| **Spatie Laravel Settings** | * | Settings management (database-backed) |
| **Laravel Pail** | ^1.2.2 | Log viewer CLI |
| **Pest PHP** | ^4.6 | Testing framework |
| **Axios** | ^1.7.4 | HTTP client untuk JavaScript |
| **Concurrently** | ^9.0.1 | Menjalankan multiple npm scripts bersamaan |

---

## 4. Panduan Instalasi

### Prasyarat
- PHP ^8.2
- Composer
- Node.js & NPM
- Database (MySQL / SQLite / PostgreSQL)

### Langkah Instalasi

```bash
# 1. Clone repository
git clone <repository-url> informasi_pemesanan_wrapping
cd informasi_pemesanan_wrapping

# 2. Install dependency Composer
composer install

# 3. Install dependency NPM
npm install

# 4. Copy file .env
cp .env.example .env

# 5. Generate APP_KEY
php artisan key:generate

# 6. Konfigurasi database di .env
#    Sesuaikan DB_CONNECTION, DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD
#    Default di .env.example menggunakan SQLite

# 7. Jalankan migration
php artisan migrate

# 8. Jalankan seeder
php artisan db:seed

# 9. Buat storage symlink (untuk upload file)
php artisan storage:link

# 10. Build assets
npm run build

# 11. Cache settings (Spatie Settings)
php artisan settings:cache

# 12. Jalankan queue worker (untuk background job)
php artisan queue:listen --tries=1

# 13. Menjalankan aplikasi (development)
composer dev
#    Perintah di atas menjalankan: php artisan serve + queue:listen + pail + npm run dev
#    secara bersamaan via concurrently

# Alternatif: Jalankan secara terpisah
php artisan serve
npm run dev
```

## 5. Informasi Tambahan

### Role yang Tersedia

| Role | Guard | Deskripsi |
|------|-------|-----------|
| `admin` | web | Akses penuh ke Filament admin panel, API admin, dan semua fitur |
| `user` | web | Akses ke customer dashboard, keranjang, dan pemesanan |

### Event & Listener

**Event-Listener Mapping** (dari `EventServiceProvider`):

| Event | Trigger | Listener |
|-------|---------|----------|
| `OrderCreated` | Customer checkout | `SendOrderConfirmationEmail` — Kirim email & notifikasi in-app ke customer |
| | | `SendOrderCreatedToAdmin` — Kirim notifikasi Filament ke semua admin |
| `OrderConfirmed` | Admin konfirmasi pesanan | `NotifyPaymentRequired` — Kirim email & SMS ke customer untuk bayar |
| `PaymentVerified` | Admin verifikasi pembayaran | `NotifyOrderProcessingStarted` — Kirim email pesanan sedang diproses |
| `OrderCompleted` | Admin selesaikan pesanan | `NotifyOrderCompleted` — Kirim email pesanan selesai |
| `OrderRejected` | Admin tolak pesanan | `NotifyOrderRejection` — Kirim email alasan penolakan |
| `PaymentUploaded` | Customer upload bukti bayar | `SendPaymentUploadedToAdmin` — Kirim notifikasi Filament ke admin |

### Queue

- **Driver:** Database (`QUEUE_CONNECTION=database`)
- **Tabel:** `jobs`
- **Penggunaan:** Belum ada job yang benar-benar di-queue. Listener saat ini berjalan _synchronously_.
- **Menjalankan:** `php artisan queue:listen --tries=1`

### Cache

- **Driver:** Database (`CACHE_STORE=database`)
- **Tabel:** `cache`
- **Penggunaan:** Cache untuk settings (`CacheService`) dan data dashboard
- **Cache Keys:**
  - `site_layanans`, `katalog_layanans`, `dashboard_layanans` (di-clear saat Layanan saved/deleted)
  - `layanan_settings`, `company_settings`, `layout_settings`, `content_settings`
- **Commands:** `php artisan optimize:clear`, `php artisan app:optimize-performance`

### API

- **Base URL:** `/api`
- **Auth:** Sanctum Bearer Token
- **Public Endpoints:**
  - `POST /api/auth/register` — Registrasi
  - `POST /api/auth/login` — Login
  - `GET /api/layanan` — Daftar layanan
  - `GET /api/layanan/{id}` — Detail layanan
  - `GET /api/layanan/kategori/{kategori}` — Filter by kategori
- **Protected Endpoints (auth:sanctum):**
  - `POST /api/auth/logout`, `GET /api/auth/me`
  - `GET/POST/PUT/DELETE /api/keranjang/*` — CRUD keranjang
  - `GET/POST /api/pesanan/*` — CRUD pesanan
  - `POST /api/pesanan/{id}/pembayaran/upload` — Upload bukti bayar
  - `GET /api/notifikasi/*` — Notifikasi
- **Admin Endpoints (role:admin):**
  - `GET/PUT /api/admin/pesanan/*` — Manajemen pesanan
  - `GET/PUT /api/admin/pembayaran/*` — Verifikasi pembayaran
  - `GET /api/admin/dashboard/stats` — Statistik dashboard
  - `GET /api/admin/dashboard/chart-data` — Data chart
- **Health:** `GET /api/health`

### Storage

- **Disk:** `public` → `storage/app/public/`
- **Symlink:** `public/storage/` → `storage/app/public/`
- **Upload directories:** `bukti_transfer/`, `layanan/`, `galeri/`

### Monitoring

- **Metrics Endpoint:** `GET /metrics` (hanya dari localhost)
  - Format: Prometheus text format (minimal)
- **Laravel Pail:** Log viewer real-time via CLI (`php artisan pail`)

### Konfigurasi Penting

- **Sanctum:** Stateful domains = localhost, localhost:3000, 127.0.0.1, 127.0.0.1:8000
- **Throttle:** 60 request per 5 menit untuk route publik
- **Session:** Database driver, lifetime 120 menit
- **Email:** Log driver (default), bisa diganti dengan SMTP di `.env`
- **Settings Cache:** Dinonaktifkan secara default (`SETTINGS_CACHE_ENABLED=false`)
- **Cart:** Maksimal 3 unique items per keranjang

---

## 6. Informasi Lain

### Tim Pengembang :
1. Yogi Maulana (362458302116)
2. Hillmi Nazwar (362458302070)
3. Zen Vero Veno Pasa (362458302072)
4. Ahmad Septa Argya Putra (362458302017)

---

### Akun Demo

| Role | Email | Password |
|------|-------|----------|
| Admin | fauziahmad@gmail.com | kelompok3 |
| User | izaldev@gmail.com | password |


## Dependensi Penting
---
### Composer Packages (Production)

| Package | Fungsi |
|---------|--------|
| `filament/filament` ^5.0 | Admin panel builder (form, table, infolist, notifications) |
| `laravel/framework` ^12.0 | Laravel core framework |
| `laravel/sanctum` ^4.3 | API token authentication |
| `spatie/laravel-permission` ^7.3 | Role & permission management |
| `spatie/laravel-settings` * | Database-backed settings management |

### Composer Packages (Development)

| Package | Fungsi |
|---------|--------|
| `laravel/breeze` ^2.4 | Authentication starter kit (Blade) |
| `laravel/pail` ^1.2.2 | CLI log viewer (real-time) |
| `pestphp/pest` ^4.6 | Testing framework |
| `laravel/sail` ^1.41 | Docker development environment |
| `laravel/pint` ^1.13 | Laravel code style fixer |
| `fakerphp/faker` ^1.23 | Fake data generator |

### NPM Packages

| Package | Fungsi |
|---------|--------|
| `tailwindcss` ^3.1 | CSS framework |
| `@tailwindcss/forms` ^0.5.2 | Tailwind form reset styles |
| `alpinejs` ^3.4.2 | JavaScript reactivity framework |
| `vite` ^6.0.11 | Build tool & dev server |
| `laravel-vite-plugin` ^1.2.0 | Laravel integration for Vite |
| `axios` ^1.7.4 | HTTP client |
| `concurrently` ^9.0.1 | Run multiple scripts in parallel |
| `postcss` ^8.4.31 | CSS post-processor |
| `autoprefixer` ^10.4.2 | CSS vendor prefixer |

---

###  Catatan

### Sanctum
- Digunakan untuk API authentication dengan token berbasis _personal access tokens_.
- Guard `api` dikonfigurasi menggunakan driver `sanctum`.
- Semua route API `/api/*` dilindungi middleware `auth:sanctum` (kecuali register, login, dan layanan publik).
- Stateful domains dikonfigurasi untuk localhost dan 127.0.0.1.

### Queue Database
- Driver queue menggunakan database (`QUEUE_CONNECTION=database`).
- Migration `0001_01_01_000002_create_jobs_table.php` menyediakan tabel `jobs`.
- Saat ini listener berjalan _synchronously_ (belum menggunakan queue async).

### Storage Public
- File upload disimpan di `storage/app/public/` dengan symlink dari `public/storage/`.
- Direktori upload: `bukti_transfer/`, `layanan/`, `galeri/`.
- Migration `storage:link` diperlukan setelah instalasi.

### Event & Listener
- 6 events dan 7 listeners mengelola notifikasi sepanjang siklus hidup pesanan.
- Mapping event-listener didefinisikan di `EventServiceProvider`.
- Filament database notifications digunakan untuk notifikasi admin real-time di panel.

### Enum
- 4 backed string enum: `OrderStatus`, `PaymentMethod`, `PaymentStatus`, `NotificationType`.
- `OrderStatus` memiliki method `label()`, `badgeColor()`, `canBeCancelled()`, `canUploadPayment()`, `validTransitions()` — state machine logic.

### Service Layer
- 5 service classes: `KeranjangService`, `PesananService`, `PembayaranService`, `NotifikasiService`, `CacheService`.
- Services di-register sebagai singleton di `AppServiceProvider`.
- `PesananService` depend on `KeranjangService`, `PembayaranService` depend on `PesananService`.

### Static Content
- Semua konten halaman publik (beranda, galeri, layanan, tentang-kami, profil) bersifat **hardcoded** di `StaticContent.php` atau langsung di view.
- Admin panel tidak bisa mengedit konten publik. Perubahan hanya via kode.
- `SettingsServiceProvider` tetap berjalan untuk menyediakan data settings ke view (`$profil` object), namun tidak digunakan untuk konten publik.

### Policies
- 3 authorization policies: `PesananPolicy`, `KeranjangPolicy`, `UserPolicy`.
- Policies didaftarkan di `AuthServiceProvider`.

### Metrics
- Endpoint `GET /metrics` di route web dengan format Prometheus text.
- Hanya bisa diakses dari localhost (127.0.0.1, ::1).
- Saat ini hanya mengembalikan metric dasar (`app_status`).

---

### Lisensi

Hak Cipta @ 2026 Fundev. Seluruh Hak Cipta Dilindungi.