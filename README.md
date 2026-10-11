# Sistem Informasi & Pemesanan Wrapping Kendaraan (Dantie Stiker)
> **Dokumentasi Resmi Arsitektur, Alur Integrasi Sistem, Panel Administrasi & Panduan Teknis Lengkap**

[![Laravel Version](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=flat-square&logo=laravel)](https://laravel.com)
[![Filament Version](https://img.shields.io/badge/Filament-5.0-F59E0B?style=flat-square&logo=filament)](https://filamentphp.com)
[![PHP Version](https://img.shields.io/badge/PHP-%5E8.2-777BB4?style=flat-square&logo=php)](https://php.net)
[![TailwindCSS](https://img.shields.io/badge/TailwindCSS-v3.1-38B2AC?style=flat-square&logo=tailwind-css)](https://tailwindcss.com)
[![Alpine.js](https://img.shields.io/badge/Alpine.js-v3.4-8BC0D0?style=flat-square&logo=alpine.js)](https://alpinejs.dev)
[![Vite](https://img.shields.io/badge/Vite-v6.0-646CFF?style=flat-square&logo=vite)](https://vitejs.dev)
[![Test Suite](https://img.shields.io/badge/Tests-96%20Passed%20(380%20assertions)-success?style=flat-square&logo=pest)](https://pestphp.com)
[![License](https://img.shields.io/badge/License-Politeknik%20Negeri%20Banyuwangi-blue?style=flat-square)](#lisensi)

---

## Daftar Isi
1. [Project Overview](#1-project-overview)
2. [Features (Fitur Utama)](#2-features-fitur-utama)
3. [Tech Stack (Teknologi Aktual)](#3-tech-stack-teknologi-aktual)
4. [Architecture (Arsitektur Sistem)](#4-architecture-arsitektur-sistem)
5. [Directory Structure](#5-directory-structure)
6. [Installation & Setup](#6-installation--setup)
7. [Environment Configuration](#7-environment-configuration)
8. [Database Schema & Migrations](#8-database-schema--migrations)
9. [Admin Panel (Filament 5.x)](#9-admin-panel-filament-5x)
10. [User Flow (Alur Pengguna)](#10-user-flow-alur-pengguna)
11. [Admin Flow (Alur Pengelola Toko)](#11-admin-flow-alur-pengelola-toko)
12. [Order Flow (Booking & Walk-In)](#12-order-flow-booking--walk-in)
13. [Payment & Verification Flow](#13-payment--verification-flow)
14. [WhatsApp Custom Flow](#14-whatsapp-custom-flow)
15. [Frontend ↔ Backend Architecture](#15-frontend--backend-architecture)
16. [Testing & Quality Assurance](#16-testing--quality-assurance)
17. [Production Deployment](#17-production-deployment)
18. [Troubleshooting & Common Issues](#18-troubleshooting--common-issues)
19. [Development Workflow](#19-development-workflow)
20. [Technology Decisions](#20-technology-decisions)
21. [Lisensi & Kontributor](#21-lisensi--kontributor)

---

## 1. Project Overview

**Sistem Informasi Pemesanan Wrapping Kendaraan (Dantie Stiker)** adalah aplikasi web terintegrasi kelas produksi yang dirancang khusus untuk digitalisasi operasional bengkel spesialis stiker dan wrapping kendaraan bermotor (mobil dan motor).

Sistem ini memecahkan 5 kendala utama bisnis wrapping fisik:
1. **Pencegahan Overbooking:** Kapasitas fisik workshop dibatasi maksimal 5 slot unit kendaraan per hari secara otomatis dengan penanganan konkurensi database dan cache lock.
2. **Standardisasi Katalog & Harga:** Menghilangkan ketidakpastian harga dengan menyediakan katalog transparan untuk berbagai tipe pengerjaan: Jasa Pasang Saja (pelanggan bawa bahan), Full Body Wrap, Roof/Partial Wrap, hingga Add-ons (nano ceramic, pembersihan lem stiker lama, kaca film).
3. **Penyatuan Pesanan Online & Kasir Offline (Walk-in):** Mengakomodasi pelanggan yang datang langsung ke bengkel tanpa akun melalui pencatatan kasir Filament atomik (`order_source = 'offline'`).
4. **Keamanan Transaksi & Invoice PDF:** Pengaturan pembayaran berjenjang (Uang Muka / DP 30% atau Lunas), verifikasi transfer admin, serta penerbitan invoice PDF resmi berformat vektor tajam.
5. **Ekosistem Review Tertutup (Closed-Loop Trust):** Ulasan dan rating bintang 1–5 hanya dapat diberikan oleh pelanggan dengan transaksi pengerjaan yang telah diverifikasi berstatus `COMPLETED`.

---

## 2. Features (Fitur Utama)

### A. Customer & Public Facing
- **Landing Page Dinamis:** Menampilkan katalog terpopuler, hero banner interaktif, portofolio galeri proyek nyata, dan testimoni terverifikasi.
- **Katalog E-Commerce Interaktif:** Navigasi katalog minimalis dengan filter kendaraan (Semua, Motor, Mobil), filter jenis layanan (Jasa Pasang, Full Wrap, Custom), pencarian judul live, dan tombol direct booking.
- **Configurator Estimasi:** Simulasi pemilihan tipe bodi dan estimasi biaya pengerjaan dengan tombol integrasi booking langsung atau konsultasi WhatsApp.
- **Sistem Booking Kalender:** Kalender kuota interaktif menampilkan ketersediaan kuota slot per hari (0–5), penutupan tanggal libur (*blocked dates*), dan pemilihan ukuran kendaraan (Small, Medium, Large).
- **Keranjang Belanja (Cart Hub):** Pengelolaan paket belanja dengan batas maksimal keamanan 3 paket aktif.
- **Hub Transaksi Terpadu (`/transaksi`):** Pelacakan status pengerjaan pesanan online dan riwayat booking dengan filter tab status (`Semua`, `Menunggu Pembayaran`, `Diproses`, `Selesai`, `Dibatalkan`).
- **Pusat Tagihan (`/transaksi/tagihan`):** Tampilan daftar tagihan terbuka yang memerlukan unggah bukti transfer.
- **Unduh Invoice PDF:** Penerbitan dokumen invoice resmi bersertifikat kode QR dan barcode.
- **Sistem Rating Tertutup:** Modal formulir ulasan bintang 1–5 dengan proteksi ujaran kasar (*ContentGuard*) dan unggah hingga 2 foto dokumentasi.

### B. Admin & Workshop Management (Filament Panel)
- **Dashboard Ringkasan Eksekutif:** Visualisasi metrik omzet, jumlah unit masuk, pemanfaatan kapasitas slot, dan pesanan terbaru.
- **Manajemen Booking Online:** Inspeksi data kendaraan, nomor plat, persetujuan slot, verifikasi bukti bayar, pembaruan status pengerjaan, dan catatan mekanik.
- **Kasir Cepat Walk-In (Offline Orders):** Formulir pemesanan tamu langsung di toko dengan metode pembayaran tunai, mesin EDC, atau transfer instan.
- **Manajemen Layanan & Addon:** Kelola harga, deskripsi, fitur checklist, kategori kendaraan, foto contoh, dan status keaktifan.
- **Master Kendaraan:** Basis data merek (*Vehicle Brand*) dan model (*Vehicle Model*) dengan klasifikasi ukuran unit (*small*, *medium*, *large*).
- **Pengaturan Tanggal Libur (*Blocked Dates*):** Kunci tanggal workshop untuk perawatan alat, hari libur nasional, atau cuti bersama.
- **Moderasi Testimoni & Balasan Resmi:** Persetujuan penayangan rating publik serta penyematan tanggapan resmi dari pemilik bengkel.
- **Laporan Finansial Terpadu (`/admin/laporan`):** Laporan rekonsiliasi arus kas yang menggabungkan seluruh transaksi booking online dan kasir fisik.

---

## 3. Tech Stack (Teknologi Aktual)

Semua dependensi dan pustaka diverifikasi langsung dari kode sumber dan berkas konfigurasi proyek:

### Backend Core
- **Framework:** Laravel 12.0 (`laravel/framework: ^12.0`)
- **PHP:** PHP 8.2+ (Typed properties, Match expressions, Enums)
- **Admin Panel:** Filament PHP 5.0 (`filament/filament: ^5.0`)
- **Dokumen PDF:** Barryvdh Laravel DomPDF 3.1 (`barryvdh/laravel-dompdf: ^3.1`)
- **Otentikasi & API Token:** Laravel Sanctum 4.3 (`laravel/sanctum: ^4.3`)
- **Hak Akses & Peran:** Spatie Laravel Permission 7.3 (`spatie/laravel-permission: ^7.3`)
- **Sistem Pengaturan Aplikasi:** Spatie Laravel Settings (`spatie/laravel-settings: *`)
- **Redis Cache Client:** Predis 3.6 (`predis/predis: ^3.6`)
- **Developer Scaffolding:** Laravel Breeze 2.4 (`laravel/breeze: ^2.4`)

### Frontend & Styling
- **Engine Tampilan:** Laravel Blade Engine
- **CSS Framework:** Tailwind CSS 3.1.0 (`tailwindcss: ^3.1.0`) dengan `@tailwindcss/forms: ^0.5.2`
- **Reactivity Client-Side:** Alpine.js 3.4.2 (`alpinejs: ^3.4.2`)
- **HTTP Client:** Axios 1.7.4 (`axios: ^1.7.4`)
- **Bundler:** Vite 6.0.11 (`vite: ^6.0.11`) melalui `laravel-vite-plugin: ^1.2.0`
- **Icon Library:** Phosphor Icons (Font Icons Web)

### Basis Data
- **Database Engine:** MySQL 8.0+ / MariaDB (Mendukung SQLite 3 pada testing otomatis)

### Quality Assurance & Testing
- **Test Runner:** Pest PHP 4.6 & PHPUnit 11
- **Mocking Library:** Mockery 1.6

---

## 4. Architecture (Arsitektur Sistem)

Aplikasi dibangun menggunakan pola arsitektur **Clean Modular MVC** yang diperkuat oleh Service Layer dan Data Transfer Object (DTO):

```
┌────────────────────────────────────────────────────────────────────────┐
│                          PRESENTATION LAYER                            │
│   Landing Pages (Blade)  │  Customer Hub (Alpine)  │  Filament Admin   │
└───────────────────────────────────┬────────────────────────────────────┘
                                    │ HTTP Requests / AJAX
┌───────────────────────────────────▼────────────────────────────────────┐
│                           ROUTING & SECURITY                           │
│   Route Model Binding  │  Sanctum Token  │  Throttling & CSRF Shield   │
│   Spatie Role Middleware ('role:admin', 'role:user')  │  ContentGuard  │
└───────────────────────────────────┬────────────────────────────────────┘
                                    │
┌───────────────────────────────────▼────────────────────────────────────┐
│                           CONTROLLER LAYER                             │
│  LandingPageController  │  BookingController  │  CustomerController   │
│  PesananController      │  RatingController   │  LaporanController    │
└───────────────────────────────────┬────────────────────────────────────┘
                                    │ Business Actions
┌───────────────────────────────────▼────────────────────────────────────┐
│                            SERVICE LAYER                               │
│  • SlotKuotaService      : Kalkulasi & Cache-Locking 5 Slot/Hari       │
│  • RatingService         : Kebijakan Tertutup, Validasi Skor & Teks    │
│  • CacheService          : Akses Cepat Pengaturan Perusahaan           │
└───────────────────────────────────┬────────────────────────────────────┘
                                    │ ORM Queries
┌───────────────────────────────────▼────────────────────────────────────┐
│                        DATA PERSISTENCE LAYER                          │
│  Eloquent Models: Booking, Pesanan, Layanan, Rating, User, Pembayaran  │
│  Database Tables: 39 Migrations dengan Foreign Keys & Index Performa   │
└────────────────────────────────────────────────────────────────────────┘
```

---

## 5. Directory Structure

```
informasi_pemesanan_wrapping/
├── app/
│   ├── Enums/                     # Status Enum (BookingStatus, PaymentStatus)
│   ├── Filament/                  # Resource, Halaman & Widget Panel Filament
│   │   └── Resources/             # 12 Filament Resources (Bookings, Pesanans, dll.)
│   ├── Helpers/                   # StaticContent, Formatters & Trait Helpers
│   ├── Http/
│   │   ├── Controllers/           # Web & AJAX Controllers
│   │   │   └── Api/               # RESTful JSON Controllers (Sanctum)
│   │   ├── Middleware/            # Role, IP Throttler, Walk-In Guards
│   │   └── Requests/              # Validasi Form (Booking, Rating, Bukti Transfer)
│   ├── Models/                    # 19 Eloquent Models dengan Relasi Konsisten
│   ├── Providers/                 # SettingsServiceProvider, AppServiceProvider
│   ├── Services/                  # SlotKuotaService, RatingService, CacheService
│   └── Settings/                  # Spatie Settings Classes (Company, Layanan, dll.)
├── config/                        # File Konfigurasi Laravel & Filament
├── database/
│   ├── factories/                 # Model Factories untuk Testing
│   ├── migrations/                # 39 Skrip Migrasi Skema Basis Data
│   └── seeders/                   # Seeder Lengkap & Idempoten
├── public/                        # Entry Point Web, Symlink Storage, Aset Statis
├── resources/
│   ├── css/                       # app.css (Tailwind Directives)
│   ├── js/                        # app.js (Alpine.js & Axios Setup)
│   └── views/                     # Blade Templates
│       ├── customer/              # Tampilan Booking, Invoice & Wizard
│       ├── dashboard/             # Dashboard Pengguna & Top Picks
│       ├── landing/               # Beranda, Katalog E-Commerce, Portofolio
│       └── layouts/               # Layout Induk Tampilan Utama & Dasbor
├── routes/
│   ├── web.php                    # Rute Antarmuka Web Publik & Terproteksi
│   ├── api.php                    # REST API Terproteksi Sanctum & Rate Limited
│   └── auth.php                   # Breeze Authentication Routes
├── storage/                       # Direktori Upload Berkas & Bukti Transfer
└── tests/
    └── Feature/                   # 96 Skenario Pengujian Fungsional (100% Pass)
```

---

## 6. Installation & Setup

### Prasyarat Sistem
- PHP >= 8.2 dengan ekstensi: `pdo`, `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `fileinfo`, `gd`, `zip`
- Composer 2.x
- Node.js >= 18.x & NPM
- MySQL 8.0+ atau MariaDB 10.4+

### Langkah-Langkah Instalasi

1. **Clone Repositori:**
   ```bash
   git clone https://github.com/username/informasi_pemesanan_wrapping.git
   cd informasi_pemesanan_wrapping
   ```

2. **Pasang Dependensi Backend:**
   ```bash
   composer install
   ```

3. **Pasang Dependensi Frontend:**
   ```bash
   npm install
   ```

4. **Konfigurasi Environment:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Konfigurasi Database pada `.env`:**
   Sesuaikan parameter koneksi basis data:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=db_wrapping
   DB_USERNAME=root
   DB_PASSWORD=
   ```

6. **Jalankan Migrasi & Seeder Database:**
   ```bash
   php artisan migrate --seed
   ```

7. **Tautkan Direktori Penyimpanan Berkas:**
   ```bash
   php artisan storage:link
   ```

8. **Kompilasi Aset Frontend:**
   ```bash
   # Mode pengembangan (HMR):
   npm run dev

   # Atau mode rilis produksi:
   npm run build
   ```

9. **Jalankan Server Lokal:**
   ```bash
   php artisan serve
   ```
   Aplikasi dapat diakses pada browser di: `http://localhost:8000`

---

## 7. Environment Configuration

Variabel konfigurasi utama yang harus diperhatikan pada file `.env`:

| Key | Default / Rekomendasi | Deskripsi |
| :--- | :--- | :--- |
| `APP_NAME` | `"Dantie Stiker"` | Nama aplikasi pada judul web dan invoice |
| `APP_ENV` | `local` (dev) / `production` | Mode lingkungan aplikasi |
| `APP_DEBUG` | `true` (dev) / `false` (prod) | Tampilan pesan error dan stack trace |
| `APP_URL` | `http://localhost:8000` | URL basis aplikasi |
| `DB_CONNECTION` | `mysql` | Driver koneksi basis data |
| `CACHE_STORE` | `file` / `redis` | Driver penyimpanan cache slot kuota |
| `SESSION_DRIVER`| `file` / `redis` | Driver sesi otentikasi |
| `FILESYSTEM_DISK`| `public` | Tempat penyimpanan bukti transfer |

---

## 8. Database Schema & Migrations

Sistem menggunakan 39 migrasi terstruktur yang menerapkan integritas referensial foreign key dan indexing performa:

```
[ users ]
   │ 1:N
   ├───< [ keranjangs ] ─── 1:N ───< [ detail_keranjangs ] >─── N:1 ───> [ layanans ]
   │
   ├───< [ pesanans ] ─── 1:N ───< [ detail_pesanans ] >─── N:1 ───> [ layanans ]
   │         │ 1:1
   │         ├───< [ form_pesanans ]
   │         ├───< [ pembayarans ]
   │
   ├───< [ bookings ] ─── 1:1 ───< [ booking_payments ]
   │         │ N:1
   │         ├───> [ layanans ]
   │         ├───> [ vehicle_models ] ─── N:1 ───> [ vehicle_brands ]
   │         └─── 1:N ───< [ booking_addons ] >─── N:1 ───> [ addons ]
   │
   └───< [ ratings ] ─── 1:N ───< [ rating_medias ]
```

### Indeks Performa Utama:
- `pesanans`: Indeks gabungan `(id_user, status)` dan `(kode_pesanan)`.
- `bookings`: Indeks `(user_id, status)`, `(booking_code)`, dan `(booking_date)`.
- `pembayarans` & `booking_payments`: Indeks status pembayaran dan tanggal transaksi untuk rekonsiliasi laporan keuangan instan.

---

## 9. Admin Panel (Filament 5.x)

Admin Panel dapat diakses melalui URL:  
`http://localhost:8000/admin`

### Kredensial Default (Hasil Seeder):
- **Email:** `fauziahmad@gmail.com`
- **Kata Sandi:** `kelompok3`
- **Peran:** `admin` (Hak akses mutlak Spatie Super-Admin)

### Akun Pelanggan Uji Coba:
- **Email:** `izaldev@gmail.com`
- **Kata Sandi:** `password`
- **Peran:** `user`

### Fitur Utama di Admin:
1. **Persetujuan Booking & Penjadwalan:** Konfirmasi ketersediaan teknisi, pembaruan status pengerjaan bertahap.
2. **Entri Kasir Walk-In:** Input langsung pelanggan fisik tanpa akun dengan nomor WhatsApp.
3. **Master Data Layanan & Addon:** Kontrol visibilitas produk (`is_active`), harga dasar, dan deskripsi pengerjaan.
4. **Pengaturan Perusahaan (Spatie Settings):** Perubahan nomor kontak WhatsApp toko, alamat, dan jam buka yang tersinkronisasi ke seluruh antarmuka web.

---

## 10. User Flow (Alur Pengguna)

```
[ Pengunjung ]
     │
     ├──► Menjelajahi Katalog E-Commerce (/katalog-layanan)
     │         │
     │         ├── Opsi A: Pilih Paket & Klik Booking Sekarang
     │         └── Opsi B: Custom Wrapping ──► Konsultasi WhatsApp
     │
     ▼
[ Otentikasi Pelanggan ] (Wajib Login)
     │
     ▼
[ Wizard Booking 4 Langkah (/booking/buat) ]
     ├── Langkah 1: Pilih Layanan (Jasa Pasang / Full Wrap)
     ├── Langkah 2: Pilih Merek & Model Kendaraan (+ Addon Ekstra)
     ├── Langkah 3: Pilih Tanggal Kalender (Cek Kuota Real-time /5)
     └── Langkah 4: Pilih Skema Bayar (DP 30% / Lunas) & Konfirmasi
     │
     ▼
[ Transaksi Terbentuk ] (Status: PENDING)
     │
     ▼ (Admin Mengonfirmasi Ketersediaan)
[ Status: CONFIRMED / AWAITING_PAYMENT ]
     │
     ▼
[ Pelanggan Mengunggah Bukti Transfer ] ──► (Status: PAYMENT_UPLOADED)
     │
     ▼ (Admin Memvalidasi Pembayaran)
[ Status: APPROVED ] (Slot Terkunci Sah)
     │
     ▼
[ Pengerjaan di Bengkel ] ──► (Status: IN_PROGRESS)
     │
     ▼
[ Unit Selesai ] ──► (Status: COMPLETED)
     │
     ├──► Unduh Invoice PDF Resmi
     └──► Berikan Rating & Ulasan Bintang 1-5
```

---

## 11. Admin Flow (Alur Pengelola Toko)

1. **Memantau Dasbor:** Memeriksa kuota hari berjalan dan notifikasi pesanan baru.
2. **Validasi Booking:**
   - Membuka menu **Bookings**.
   - Memeriksa kelayakan jenis bahan dan waktu pengerjaan.
   - Mengubah status ke `confirmed` untuk menerbitkan instruksi transfer rekening bagi pelanggan.
3. **Pemeriksaan Bukti Pembayaran:**
   - Melihat bukti struk transfer di tab pembayaran.
   - Memilih tombol aksi `Setujui Pembayaran` (status berubah menjadi `approved`).
4. **Pembaruan Pengerjaan:**
   - Saat kendaraan tiba di bengkel, ubah status ke `in_progress`.
   - Setelah stiker selesai dipasang dan lolos QC, ubah ke `completed`.
5. **Entri Walk-In:** Menginput pelanggan offline melalui menu **Pesanans** &rarr; tombol `Buat Pesanan Walk-In`.

---

## 12. Order Flow (Booking & Walk-In)

| Parameter | Booking Online Mandiri | Pesanan Walk-In Kasir |
| :--- | :--- | :--- |
| **Inisiator** | Pelanggan melalui web browser | Admin kasir di bengkel fisik |
| **Persyaratan Akun** | Wajib Register & Login | Fleksibel: Mode Tamu (*Guest*) atau Akun Member |
| **Batas Slot Harian** | Terkunci ketat maksimal 5 unit/hari | Dapat menggunakan *Admin Override* jika darurat |
| **Metode Pembayaran** | Transfer Bank / E-Wallet + Unggah Struk | Tunai / Cash, Kartu EDC, atau QRIS Kasir |
| **Verifikasi Kasir** | 2 Tahap: User Upload &rarr; Admin Validasi | Instan di kasir toko (*Directly Verified*) |
| **Format Kode** | `BKG-YYYYMMDD-XXXXX` | `PSN-OFF-XXXXXX` |
| **Dokumen Bukti** | Invoice PDF Resmi Otomatis | Kwitansi Kasir & SPK Fisik Toko |

---

## 13. Payment & Verification Flow

Sistem mengadopsi mekanisme pembayaran fleksibel:
1. **Down Payment (DP 30%):** Dihitung dinamis minimal 30% dari total nilai transaksi (atau batas nominal dasar Rp 500.000) untuk mengikat alokasi teknisi tanpa membebani pelanggan di muka.
2. **Pelunasan Penuh (Lunas 100%):** Membayar total tagihan di awal.
3. **Rekonsiliasi Pelunasan Sisa:** Pelanggan yang memilih opsi DP dapat melunasi sisa tagihan secara tunai atau transfer saat pengambilan kendaraan di bengkel.
4. **Keamanan Upload Berkas Bukti:**
   - File dibatasi maksimal 5 MB.
   - Format wajib: `image/jpeg`, `image/png`, `image/webp`, atau `application/pdf`.
   - Berkas disimpan di disk terisolasi dengan nama hash terenkripsi anti-eksekusi skrip berbahaya.

---

## 14. WhatsApp Custom Flow

Seluruh pesanan khusus atau layanan non-standar diarahkan secara terpusat ke saluran WhatsApp resmi:
- **Kebutuhan Custom:** Permintaan desain stiker modifikasi kompleks, stiker printing kustom, atau jenis kendaraan khusus yang tidak tercantum di katalog.
- **Sumber Nomor Terpusat:** Nomor WhatsApp tidak di-*hardcode* di sembarang tempat. Nilai diambil secara terpusat dari konfigurasi Spatie Company Settings (`$profil->nomor_telepon`), dengan fallback ke `StaticContent::COMPANY_WHATSAPP` (`083117163605`).
- **Template Pesan Otomatis:**
  Ketika pelanggan mengklik tombol konsultasi custom, sistem secara otomatis mengompilasi draf pesan teks:
  ```
  Halo Tim Dantie Stiker, saya ingin konsultasi konsep desain & estimasi harga proyek Custom stiker kendaraan [Motor/Mobil]...
  ```

---

## 15. Frontend ↔ Backend Architecture

Integrasi data antara Frontend dan Backend mematuhi aturan **Admin Panel &rarr; Database &rarr; Backend &rarr; Frontend**:
- **Bukan Data Hardcoded:** Judul layanan, paket harga, checklist fitur, foto contoh, merek kendaraan, dan portofolio diambil murni dari basis data melalui Eloquent ORM.
- **Eager Loading Anti-N+1:** Semua query relasi menggunakan `with()` dan `withCount()` untuk performa optimal.
- **Aesthetic UI:** Menggunakan tema minimalis e-commerce dengan kartu produk modern, palet warna oranye aksen `#ff6b00`, kontras tinggi, typography modern (*Audiowide*, *Montserrat*, *Questrial*), dan responsivitas penuh.

---

## 16. Testing & Quality Assurance

Sistem dilengkapi suite pengujian otomatis komprehensif menggunakan Pest PHP & PHPUnit dengan tingkat keberhasilan **100% PASS**:

```bash
# Menjalankan seluruh rangkaian pengujian
php artisan test
```

### Ringkasan Hasil Uji Otomatis:
- **Total Pengujian:** 96 Tests Passed (0 Failed)
- **Total Asersi:** 380 Assertions
- **Cakupan Pengujian:**
  - `Authentication & Breeze Flow` (Login, Register, Password Reset)
  - `Access Control & Role Security` (Admin Isolation, 403 Forbidden checks)
  - `IDOR Protection` (Pelanggan dilarang mengakses booking/pesanan/invoice pengguna lain)
  - `Business Flow & Addons` (Validasi pemilihan paket, kalkulasi DP, model kendaraan)
  - `Closed-Loop Ratings` (Pencegahan rating sebelum pengerjaan selesai, ContentGuard ujaran kasar, idempotensi 1 ulasan/transaksi)
  - `Financial Reporting` (Konsolidasi data transaksi online dan offline pada laporan rugi-laba)

---

## 17. Production Deployment

Langkah-langkah untuk *deployment* di server produksi (Linux Ubuntu / Nginx):

1. **Optimasi Konfigurasi & Cache Laravel:**
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   php artisan event:cache
   ```
2. **Kompilasi Aset Produksi:**
   ```bash
   npm ci
   npm run build
   ```
3. **Set Hak Akses Direktori:**
   ```bash
   chown -R www-data:www-data storage bootstrap/cache
   chmod -R 775 storage bootstrap/cache
   ```
4. **Konfigurasi Web Server (Nginx):**
   Arahkan `root` web server ke folder `/public` dan pastikan file upload `client_max_body_size` diset minimal 10M.
5. **Supervisor untuk Queue Worker (Jika Digunakan):**
   Jalankan `php artisan queue:work` melalui Daemon Supervisor.

---

## 18. Troubleshooting & Common Issues

### 1. Error 419 (Page Expired) Saat Logout / Form Submit
- **Sebab:** Sesi kedaluwarsa atau token CSRF tidak cocok.
- **Solusi:** Sistem telah menyediakan rute penyelamat `GET /logout` yang langsung membersihkan session dan token pengguna dengan aman.

### 2. Gambar Layanan / Bukti Transfer Tidak Muncul
- **Sebab:** Symlink direktori storage belum dibuat.
- **Solusi:** Jalankan perintah `php artisan storage:link`.

### 3. Kuota Kalender Tanggal Tertentu Terkunci Padahal Belum 5 Unit
- **Sebab:** Tanggal tersebut terdaftar pada tabel `blocked_dates` (jadwal libur workshop).
- **Solusi:** Buka Filament Admin &rarr; menu **Blocked Dates** &rarr; sesuaikan status tanggal yang ditutup.

---

## 19. Development Workflow

1. Buat branch baru dari `main` untuk setiap fitur:
   ```bash
   git checkout -b feature/nama-fitur
   ```
2. Tulis kode implementasi dengan kepatuhan standar PSR-12.
3. Tulis pengujian fitur di `tests/Feature/`.
4. Jalankan pengujian dan pastikan 100% lolos:
   ```bash
   php artisan test
   ```
5. Buat pull request menuju branch `main`.

---

## 20. Technology Decisions

| Keputusan Teknologi | Alasan Arsitektural |
| :--- | :--- |
| **Laravel 12 + Filament 5** | Kombinasi tercepat dan terandal untuk menghasilkan panel administrasi kaya fitur tanpa menulis ratusan form CRUD manual. |
| **Closed-Loop Rating** | Menjamin integritas ulasan dan portofolio bengkel hanya berasal dari transaksi yang nyata dan telah selesai dikerjakan. |
| **Split-Payment (DP 30%)** | Menurunkan hambatan belanja (*purchase barrier*) untuk layanan bernilai tinggi sekaligus melindungi bengkel dari risiko pelanggan mangkir (*no-show*). |
| **Penyatuan Pesanan Walk-In** | Menjaga akurasi pembukuan finansial bengkel agar tidak ada perbedaan antara transaksi kasir fisik dan pesanan web. |
| **Integrasi WhatsApp Sentral** | Memudahkan negosiasi kebutuhan stiker custom yang membutuhkan asistensi manusia tanpa membuat sistem form kustom yang kaku. |

---

## 21. Lisensi & Kontributor

Aplikasi ini dikembangkan dalam rangka kemitraan Proyek Berbasis Masalah (PBL) antara **Politeknik Negeri Banyuwangi (POLIWANGI)** dan **Bengkel Dantie Stiker**.

Hak Cipta &copy; 2026 Bengkel Dantie Stiker & Tim Pengembang POLIWANGI. Seluruh hak cipta dilindungi undang-undang.
