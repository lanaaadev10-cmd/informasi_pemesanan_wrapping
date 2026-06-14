# ANALISIS FITUR SISTEM INFORMASI PEMESANAN WRAPPING

**Dibuat:** 7 Juni 2026  
**Framework:** Laravel 11 + Filament v5 + Sanctum API + Spatie Permission  
**Total File:** 200+ file (controllers, models, services, views, migrations, dll)

---

## DAFTAR ISI

1. [Sistem Autentikasi dan Akun](#1-sistem-autentikasi-dan-akun)
2. [Profil Perusahaan](#2-profil-perusahaan)
3. [Galeri Hasil Pekerjaan](#3-galeri-hasil-pekerjaan)
4. [Katalog Layanan](#4-katalog-layanan)
5. [Pemesanan](#5-pemesanan)
6. [Keranjang](#6-keranjang)
7. [Checkout](#7-checkout)
8. [Riwayat Pemesanan](#8-riwayat-pemesanan)
9. [Admin Profil Perusahaan](#9-admin-profil-perusahaan)
10. [Admin Galeri](#10-admin-galeri)
11. [Admin Katalog](#11-admin-katalog)
12. [Admin Pemesanan](#12-admin-pemesanan)
13. [Admin Keranjang](#13-admin-keranjang)
14. [Admin Riwayat Pemesanan](#14-admin-riwayat-pemesanan)
15. [Admin Verifikasi Pembayaran dan Koreksi Harga](#15-admin-verifikasi-pembayaran-dan-koreksi-harga)

---

## 1. SISTEM AUTENTIKASI DAN AKUN

### 1.1 Tujuan Fitur

- **Fungsi bisnis:** Mengelola registrasi, login, logout, verifikasi email, reset password, dan profil pengguna (admin & customer).
- **Kebutuhan pengguna:** Pengguna dapat mendaftar, masuk, keluar, mengubah profil, dan menghapus akun.

### 1.2 Lokasi Implementasi

| Komponen | Lokasi |
|---|---|
| **Route Web** | `routes/auth.php` (login, register, forgot/reset password, verify email, confirm password, logout) |
| **Route Web** | `routes/web.php:43-48` (dashboard, profile: edit/update/destroy) |
| **Route API** | `routes/api.php:24-25` (POST /auth/register, POST /auth/login) |
| **Route API** | `routes/api.php:41-42` (POST /auth/logout, GET /auth/me) |
| **Route Web API** | `routes/web.php:130-134` (api/user/profile, api/user/password, api/user/dashboard/stats) |
| **Controller Web** | `app/Http/Controllers/Auth/*` (9 controllers: AuthenticatedSession, RegisteredUser, Password*, VerifyEmail, ConfirmablePassword) |
| **Controller Web** | `app/Http/Controllers/ProfileController.php` (edit, update, destroy) |
| **Controller API** | `app/Http/Controllers/Api/AuthController.php` (register, login, logout, me) |
| **Controller API Web** | `app/Http/Controllers/Api/UserProfileApiController.php` (show, update, updatePassword, destroy, dashboardStats) |
| **Model** | `app/Models/User.php` |
| **Request** | `app/Http/Requests/Auth/RegisterRequest.php` (validasi register API) |
| **Request** | `app/Http/Requests/Auth/LoginRequest.php` (validasi login + rate limiting) |
| **Request** | `app/Http/Requests/ProfileUpdateRequest.php` (validasi update profil) |
| **View** | `resources/views/auth/*` (login, register, forgot-password, reset-password, verify-email, confirm-password) |
| **View** | `resources/views/profile/*` (edit, partials/*) |
| **View** | `resources/views/layouts/app.blade.php`, `layouts/guest.blade.php` |
| **Middleware** | `app/Http/Middleware/RoleMiddleware.php` |
| **Resource Filament** | `app/Filament/Resources/Users/*` (UserResource, UserForm, UsersTable) |

### 1.3 Struktur File

```
routes/auth.php
routes/web.php         (baris 43-48: profile routes)
routes/api.php         (baris 24-25, 41-42: auth API)
app/Http/Controllers/Auth/
  AuthenticatedSessionController.php
  ConfirmablePasswordController.php
  EmailVerificationNotificationController.php
  EmailVerificationPromptController.php
  NewPasswordController.php
  PasswordController.php
  PasswordResetLinkController.php
  RegisteredUserController.php
  VerifyEmailController.php
app/Http/Controllers/ProfileController.php
app/Http/Controllers/Api/AuthController.php
app/Http/Controllers/Api/UserProfileApiController.php
app/Models/User.php
app/Http/Middleware/RoleMiddleware.php
app/Http/Requests/Auth/LoginRequest.php
app/Http/Requests/Auth/RegisterRequest.php
app/Http/Requests/ProfileUpdateRequest.php
resources/views/auth/*
resources/views/profile/*
resources/views/layouts/app.blade.php
resources/views/layouts/guest.blade.php
app/Filament/Resources/Users/*
app/Policies/UserPolicy.php
database/migrations/0001_01_01_000000_create_users_table.php
database/seeders/RolesTableSeeder.php
database/seeders/PermissionsTableSeeder.php
database/seeders/UserTableSeeder.php
```

### 1.4 Alur Kerja Fitur

**Registrasi Web:**
```
User buka /register
→ routes/auth.php: GET /register (RegisteredUserController@create)
→ Tampilkan view auth.register
→ User isi form & submit POST /register
→ Validasi (name, email, password, password_confirmation)
→ Buat User + assign role 'user' via Spatie
→ Login otomatis
→ Redirect ke /dashboard
```

**Login API (Sanctum):**
```
Customer POST /api/auth/login {email, password}
→ Api\AuthController@login
→ Validasi via LoginRequest
→ Hash::check password
→ Buat PersonalAccessToken (Sanctum)
→ Return 200: {user, token}
```

**Login Web:**
```
User buka /login
→ routes/auth.php: GET /login (AuthenticatedSessionController@create)
→ Submit POST /login
→ Validasi email + password
→ Rate limiter (max 5 attempts)
→ Redirect ke /dashboard
```

### 1.5 Database

**Tabel:** `users`

| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint UNSIGNED AUTO_INCREMENT | Primary Key |
| name | VARCHAR(255) | NOT NULL |
| email | VARCHAR(255) | UNIQUE, NOT NULL |
| email_verified_at | TIMESTAMP | NULLABLE |
| password | VARCHAR(255) | NOT NULL (hashed) |
| remember_token | VARCHAR(100) | NULLABLE |

**Relasi:**
- User → Keranjang (hasMany via id_user)
- User → Pesanan (hasMany via id_user)
- User → Notifikasi (hasMany via id_user)

**Migration terkait:**
- `0001_01_01_000000_create_users_table.php`
- `2026_04_20_161226_create_permission_tables.php` (role & permission)

**Seeder terkait:**
- `UserTableSeeder.php` (admin: Ahmad Fauzi, user: Syahrizaldev)
- `RolesTableSeeder.php` (admin, user)
- `PermissionsTableSeeder.php` (12 permissions)

### 1.6 Keamanan

| Aspek | Implementasi |
|---|---|
| **Authentication** | Laravel Breeze (session), Sanctum Token (API) |
| **Authorization** | Spatie roles (`admin` / `user`), `RoleMiddleware` |
| **Rate Limiting** | Login: max 5 attempts via `LoginRequest`; Web: throttle 60/5min; API: throttle 60/1min |
| **Password** | Hash::make, current_password rule untuk delete & change |
| **Email Verification** | Signed URL, throttle 6/1min |
| **CSRF** | Laravel built-in CSRF protection (kecuali API) |
| **Policy** | `UserPolicy`: hanya admin bisa manage users; admin tidak bisa hapus diri sendiri |

### 1.7 Integrasi dengan Fitur Lain

- **Profil Perusahaan** (login_title, login_subtitle, register_title dari CMS)
- **Admin Users** (manajemen user via Filament)
- **Pemesanan** (user memiliki pesanan)
- **Keranjang** (user memiliki keranjang)
- **Notifikasi** (user menerima notifikasi)

### 1.8 Status Implementasi

**Lengkap** – Seluruh alur auth (web + API) berfungsi penuh.

---

## 2. PROFIL PERUSAHAAN

### 2.1 Tujuan Fitur

- **Fungsi bisnis:** Menampilkan informasi perusahaan (tentang kami, visi, misi, sejarah, kontak, sosial media) kepada pengunjung website.
- **Kebutuhan pengguna:** Pengunjung dapat melihat profil perusahaan, lokasi maps, dan informasi kontak.

### 2.2 Lokasi Implementasi

| Komponen | Lokasi |
|---|---|
| **Route Web** | `routes/web.php:24-26` (GET /profil-perusahaan, /tentang-kami, /layanan) |
| **Controller** | `app/Http/Controllers/DashboardController.php` (profile, tentangKami) |
| **Model** | `app/Models/ProfilPerusahaan.php` |
| **Model Trait** | `app/Models/Traits/HasCompanyCms.php` |
| **View** | `resources/views/landing/profil/index.blade.php` |
| **View** | `resources/views/landing/tentang-kami/index.blade.php` |
| **View (partial)** | `resources/views/landing/tentang-kami/_hero.blade.php` |
| **View (partial)** | `resources/views/landing/tentang-kami/_history.blade.php` |
| **View (partial)** | `resources/views/landing/tentang-kami/_vision-mission.blade.php` |
| **View (partial)** | `resources/views/landing/tentang-kami/_values.blade.php` |
| **View (partial)** | `resources/views/landing/tentang-kami/_team.blade.php` |
| **View (partial)** | `resources/views/landing/tentang-kami/_cta.blade.php` |
| **View (partial)** | `resources/views/landing/tentang-kami/_style.blade.php` |

### 2.3 Struktur File

```
routes/web.php (baris 24-26)
app/Http/Controllers/DashboardController.php
  -> profile()
  -> tentangKami()
app/Models/ProfilPerusahaan.php
app/Models/Traits/HasCompanyCms.php
resources/views/landing/profil/index.blade.php
resources/views/landing/tentang-kami/index.blade.php
resources/views/landing/tentang-kami/_hero.blade.php
resources/views/landing/tentang-kami/_history.blade.php
resources/views/landing/tentang-kami/_vision-mission.blade.php
resources/views/landing/tentang-kami/_values.blade.php
resources/views/landing/tentang-kami/_team.blade.php
resources/views/landing/tentang-kami/_cta.blade.php
resources/views/landing/tentang-kami/_style.blade.php
database/migrations/2026_04_21_055219_create_profil_perusahaans_table.php
database/migrations/*_add_*cms*_to_profil_perusahaans* (18 migrasi tambahan)
database/seeders/ProfilPerusahaanSeeder.php
```

### 2.4 Alur Kerja Fitur

```
User buka /profil-perusahaan
→ DashboardController@profile()
→ Cache::remember('site_profile', 3600, ...) ambil ProfilPerusahaan::first()
→ Return view landing.profil.index (compact profil)
→ View menampilkan: nama, deskripsi, alamat, email, telepon, logo, maps
```

```
User buka /tentang-kami
→ DashboardController@tentangKami()
→ Cache::remember('site_profile')
→ Return view landing.tentang-kami.index (compact profil)
→ View menampilkan: hero, visi, misi, sejarah, values, team, CTA
```

### 2.5 Database

**Tabel:** `profil_perusahaans`  
Model singleton (hanya 1 record, id=1). Tabel ini memiliki **100+ kolom** yang terus bertambah via migrasi CMS. Kolom inti:

| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint UNSIGNED | PK |
| nama_perusahaan | VARCHAR(255) | Nama perusahaan |
| deskripsi | TEXT | Deskripsi |
| alamat | VARCHAR(255) | Alamat |
| email | VARCHAR(255) | Email |
| nomor_telepon | VARCHAR(255) | Telepon |
| logo | VARCHAR(255) | NULLABLE, path logo |
| maps_url | TEXT | NULLABLE, embed Google Maps |
| visi, misi, sejarah | TEXT/LONGTEXT | HTML content |
| tentang_kami_hero_title/desc/image | TEXT/VARCHAR | Hero section |
| tentang_kami_team_members | JSON | Data tim |
| instagram/fb/tiktok/whatsapp_url | VARCHAR(255) | Social media |
| meta_title, meta_description | TEXT | SEO |

**Relasi:** Tidak ada (model singleton)

**Migration terkait:** 19 file migrasi (create + 18 kali ALTER untuk CMS fields)

### 2.6 Keamanan

| Aspek | Implementasi |
|---|---|
| **Authentication** | Tidak (halaman publik) |
| **Authorization** | Tidak |
| **Validation** | Tidak (read-only) |
| **Middleware** | throttle:60,5 |
| **Cache** | site_profile (3600 detik), di-flush saat ProfilPerusahaan saved/deleted |

### 2.7 Integrasi dengan Fitur Lain

- **Admin Profil Perusahaan** (CMS edit via Filament: BerandaResource, TentangKamiResource)
- **Beranda** (data profil digunakan di landing.beranda.index)
- **Semua halaman publik** (navbar menggunakan logo & kontak dari profil)

### 2.8 Status Implementasi

**Lengkap** – Halaman publik berfungsi penuh dengan data dari database.

---

## 3. GALERI HASIL PEKERJAAN

### 3.1 Tujuan Fitur

- **Fungsi bisnis:** Menampilkan portofolio hasil pekerjaan wrapping/stiker kepada pengunjung.
- **Kebutuhan pengguna:** Pengunjung dapat melihat galeri foto, filter berdasarkan kategori, dan melihat detail pekerjaan.

### 3.2 Lokasi Implementasi

| Komponen | Lokasi |
|---|---|
| **Route Web** | `routes/web.php:27` (GET /galeri-karya) |
| **Controller** | `app/Http/Controllers/GaleriController.php` (index) |
| **Model** | `app/Models/Galeri.php` |
| **View** | `resources/views/landing/galeri/index.blade.php` |
| **View (partial)** | `resources/views/landing/galeri/_hero.blade.php` |
| **View (partial)** | `resources/views/landing/galeri/_filter.blade.php` |
| **View (partial)** | `resources/views/landing/galeri/_grid.blade.php` |
| **View (partial)** | `resources/views/landing/galeri/_script.blade.php` |

### 3.3 Struktur File

```
routes/web.php (baris 27)
app/Http/Controllers/GaleriController.php -> index()
app/Models/Galeri.php
resources/views/landing/galeri/index.blade.php
resources/views/landing/galeri/_hero.blade.php
resources/views/landing/galeri/_filter.blade.php
resources/views/landing/galeri/_grid.blade.php
resources/views/landing/galeri/_script.blade.php
database/migrations/2026_05_05_164310_create_galeris_table.php
database/migrations/2026_05_11_175341_add_kategori_to_galeris_table.php
database/migrations/2026_05_11_181232_add_details_to_galeris_table.php
database/seeders/GaleriSeeder.php
```

### 3.4 Alur Kerja Fitur

```
User buka /galeri-karya
→ GaleriController@index()
→ Cache::remember('site_galeris', 3600, Galeri::latest()->get())
→ Cache::remember('site_profile', 3600, ProfilPerusahaan::first())
→ Return view landing.galeri.index (compact galeri, profil)
→ View menampilkan: hero, filter kategori, grid galeri dengan foto
```

### 3.5 Database

**Tabel:** `galeris`

| Kolom | Tipe | Keterangan |
|---|---|---|
| id_galeri | bigint UNSIGNED AUTO_INCREMENT | Primary Key |
| judul | VARCHAR(255) | NOT NULL |
| sub_judul | VARCHAR(255) | NULLABLE |
| foto | VARCHAR(255) | NOT NULL (path gambar) |
| deskripsi | TEXT | NULLABLE |
| kategori | VARCHAR(255) | NULLABLE (misal: wrapping, stiker, chrome_delete) |
| badge_text | VARCHAR(255) | DEFAULT 'Featured Project' |
| is_featured | BOOLEAN/TINYINT | DEFAULT false |
| tanggal_upload | DATE | NOT NULL |

**Relasi:** Tidak ada

**Migration terkait:**
- `2026_05_05_164310_create_galeris_table.php`
- `2026_05_11_175341_add_kategori_to_galeris_table.php`
- `2026_05_11_181232_add_details_to_galeris_table.php`

**Seeder:** `GaleriSeeder.php` (5 data awal)

### 3.6 Keamanan

| Aspek | Implementasi |
|---|---|
| **Authentication** | Tidak (halaman publik) |
| **Authorization** | Tidak |
| **Middleware** | throttle:60,5 |
| **Cache** | site_galeris (3600s), site_galeris_home (3600s), di-flush saat Galeri saved/deleted |

### 3.7 Integrasi dengan Fitur Lain

- **Admin Galeri** (CRUD via Filament)
- **Beranda** (8 galeri terbaru ditampilkan di halaman utama via `site_galeris_home`)
- **Halaman Galeri CMS** (hero title/desc dari ProfilPerusahaan.galeri_*)

### 3.8 Status Implementasi

**Lengkap** – Halaman galeri publik dengan filter dan grid berfungsi penuh.

---

## 4. KATALOG LAYANAN

### 4.1 Tujuan Fitur

- **Fungsi bisnis:** Menampilkan daftar layanan/paket wrapping yang ditawarkan beserta harga, fitur, dan foto.
- **Kebutuhan pengguna:** Pengunjung dapat melihat katalog, filter kategori, dan langsung menambahkan ke keranjang atau memesan.

### 4.2 Lokasi Implementasi

| Komponen | Lokasi |
|---|---|
| **Route Web** | `routes/web.php:28` (GET /katalog-layanan) |
| **Route Web API** | `routes/web.php:111-114` (GET /api/layanans, /categories, /{id}, /kategori/{kategori}) |
| **Route API** | `routes/api.php:28-32` (GET /api/layanan, /{layanan}, /kategori/{kategori}) |
| **Controller Web** | `app/Http/Controllers/CustomerController.php` (katalog) |
| **Controller Web API** | `app/Http/Controllers/Api/LayananApiController.php` (index, show, byCategory, categories) |
| **Controller API** | `app/Http/Controllers/Api/LayananController.php` (index, show, filterByCategory) |
| **Model** | `app/Models/Layanan.php` |
| **Resource API** | `app/Http/Resources/LayananResource.php` |
| **View** | `resources/views/landing/katalog/index.blade.php` |
| **View (partial)** | `resources/views/landing/katalog/_header.blade.php` |
| **View (partial)** | `resources/views/landing/katalog/_intro.blade.php` |
| **View (partial)** | `resources/views/landing/katalog/_features.blade.php` |
| **View (partial)** | `resources/views/landing/katalog/_grid.blade.php` |
| **View (partial)** | `resources/views/landing/katalog/_cart-button.blade.php` |
| **View (partial)** | `resources/views/landing/katalog/_script.blade.php` |
| **View (partial)** | `resources/views/components/packages-carousel.blade.php` |

### 4.3 Struktur File

```
routes/web.php (baris 28)
routes/web.php (baris 111-114: layanans API)
routes/api.php (baris 28-32: layanan API)
app/Http/Controllers/CustomerController.php -> katalog()
app/Http/Controllers/Api/LayananApiController.php
app/Http/Controllers/Api/LayananController.php
app/Models/Layanan.php
app/Http/Resources/LayananResource.php
app/Http/Requests/Keranjang/AddToCartRequest.php
resources/views/landing/katalog/*
resources/views/components/packages-carousel.blade.php
resources/views/frontend/katalog/index.blade.php
app/Filament/Resources/Layanans/*
database/migrations/2026_04_21_132536_create_layanans_table.php
database/migrations/2026_05_11_* (5 migrasi tambahan)
database/seeders/LayananSeeder.php
```

### 4.4 Alur Kerja Fitur

**Halaman Katalog Publik:**
```
User buka /katalog-layanan
→ CustomerController@katalog()
→ Layanan::all() + ProfilPerusahaan via cache
→ Return view landing.katalog.index
→ Grid menampilkan semua layanan dengan foto, nama, harga, fitur, tombol "Pesan"
```

**API Katalog (Web):**
```
GET /api/layanans?kategori=mobil&search=wrap
→ LayananApiController@index()
→ Filter Layanan by kategori, tipe_layanan, tipe_paket, search (LIKE nama/deskripsi)
→ Paginate (default 20)
→ Return JSON {status, data: [{layanan}], pagination}
```

**API Katalog (Sanctum):**
```
GET /api/layanan?kategori=mobil&per_page=12
→ LayananController@index()
→ Paginate dengan filter kategori, tipe_layanan, tipe_paket
→ Return LayananResource collection
```

### 4.5 Database

**Tabel:** `layanans`

| Kolom | Tipe | Keterangan |
|---|---|---|
| id_layanan | bigint UNSIGNED AUTO_INCREMENT | Primary Key |
| nama_layanan | VARCHAR(255) | NOT NULL |
| deskripsi | TEXT | NULLABLE |
| harga | INTEGER | NULLABLE (untuk tipe fix) |
| tipe_layanan | ENUM('fix','custom') | NOT NULL |
| tipe_paket | VARCHAR(255) | NULLABLE (Standard, Premium, Custom) |
| kategori | VARCHAR(255) | DEFAULT 'mobil' (mobil, motor, stiker) |
| fitur | JSON | NULLABLE (array of fitur) |
| foto_contoh | VARCHAR(255) | NULLABLE (path gambar) |
| estimasi_waktu | VARCHAR(100) | NULLABLE (contoh: "3-5 Hari Kerja") |

**Relasi:**
- Layanan → DetailKeranjang (hasMany via id_paket)
- Layanan → DetailPesanan (hasMany via id_paket)

**Migration:** 7 file (create + 6 ALTER)
**Seeder:** `LayananSeeder.php` (5 layanan awal)

### 4.6 Keamanan

| Aspek | Implementasi |
|---|---|
| **Authentication** | Tidak untuk halaman publik |
| **Rate Limiting** | Web: throttle 60/5min; API web: throttle 60/1min |
| **Cache** | site_layanans (3600s), katalog_layanans, dashboard_layanans, layanan_{id}, layanan_categories; di-flush saat Layanan saved/deleted |

### 4.7 Integrasi dengan Fitur Lain

- **Keranjang** (tambah ke keranjang dari katalog)
- **Pemesanan** (direct order dari katalog)
- **Admin Katalog** (CRUD via Filament LayananResource)
- **Dashboard Pelanggan** (paket ditampilkan di dashboard)

### 4.8 Status Implementasi

**Lengkap** – Halaman katalog + API publik berfungsi penuh.

---

## 5. PEMESANAN

### 5.1 Tujuan Fitur

- **Fungsi bisnis:** Mengelola pemesanan jasa wrapping dari pelanggan dengan alur 7 status.
- **Kebutuhan pengguna:** Pelanggan dapat membuat pesanan, melihat status, upload bukti bayar, dan melihat invoice.

### 5.2 Lokasi Implementasi

| Komponen | Lokasi |
|---|---|
| **Route Web** | `routes/web.php:65-96` (prefix pesanan: index, buat, checkout, show, invoice, upload-bukti) |
| **Route Web API** | `routes/web.php:117-122` (GET/POST /api/pesanan, /{id}/status, /{id}/upload-bukti, /{id}/timeline) |
| **Route API** | `routes/api.php:56-68` (GET/POST /api/pesanan, cancel, invoice, pembayaran) |
| **Controller Web** | `app/Http/Controllers/PesananController.php` |
| **Controller Web API** | `app/Http/Controllers/Api/PesananApiController.php` |
| **Controller API** | `app/Http/Controllers/Api/PesananController.php` |
| **Service** | `app/Services/PesananService.php` |
| **Service** | `app/Services/PembayaranService.php` |
| **Model** | `app/Models/Pesanan.php` |
| **Model** | `app/Models/DetailPesanan.php` |
| **Model** | `app/Models/FormPesanan.php` |
| **Model** | `app/Models/Pembayaran.php` |
| **Model** | `app/Models/Notifikasi.php` |
| **Event** | `app/Events/OrderCreated.php`, `OrderConfirmed.php`, `PaymentUploaded.php`, `PaymentVerified.php`, `OrderCompleted.php`, `OrderRejected.php` |
| **Listener** | 7 listener (notifikasi + email) |
| **Enum** | `app/Enums/OrderStatus.php` (7 status + state machine) |
| **Enum** | `app/Enums/PaymentStatus.php`, `PaymentMethod.php` |
| **Request** | `app/Http/Requests/Pesanan/CheckoutRequest.php` |
| **Request** | `app/Http/Requests/Pembayaran/UploadPaymentProofRequest.php` |
| **Request** | `app/Http/Requests/Admin/Pesanan/UpdateOrderStatusRequest.php` |
| **Policy** | `app/Policy/PesananPolicy.php` |
| **View** | `resources/views/dashboard/customer/pesanan/index.blade.php` |
| **View** | `resources/views/dashboard/customer/pesanan/show.blade.php` |
| **View** | `resources/views/dashboard/customer/pesanan/invoice.blade.php` |
| **View** | `resources/views/dashboard/customer/pesanan/checkout.blade.php` |
| **View** | `resources/views/dashboard/customer/pesanan/direct-order.blade.php` |

### 5.3 Struktur File

```
routes/web.php (baris 65-96)
routes/web.php (baris 117-122)
routes/api.php (baris 56-68)
app/Http/Controllers/PesananController.php
app/Http/Controllers/Api/PesananApiController.php
app/Http/Controllers/Api/PesananController.php
app/Services/PesananService.php
app/Services/PembayaranService.php
app/Models/Pesanan.php
app/Models/DetailPesanan.php
app/Models/FormPesanan.php
app/Models/Pembayaran.php
app/Http/Resources/PesananResource.php
app/Http/Resources/DetailPesananResource.php
app/Http/Resources/FormPesananResource.php
app/Http/Resources/PembayaranResource.php
app/Events/OrderCreated.php, OrderConfirmed.php, PaymentUploaded.php, PaymentVerified.php, OrderCompleted.php, OrderRejected.php
app/Listeners/SendOrderCreatedToAdmin.php
app/Listeners/SendOrderConfirmationEmail.php
app/Listeners/SendPaymentUploadedToAdmin.php
app/Listeners/NotifyPaymentRequired.php
app/Listeners/NotifyOrderProcessingStarted.php
app/Listeners/NotifyOrderCompleted.php
app/Listeners/NotifyOrderRejection.php
app/Enums/OrderStatus.php
app/Enums/PaymentStatus.php
app/Enums/PaymentMethod.php
app/Http/Requests/Pesanan/CheckoutRequest.php
app/Http/Requests/Pembayaran/UploadPaymentProofRequest.php
app/Http/Requests/Admin/Pesanan/UpdateOrderStatusRequest.php
app/Policies/PesananPolicy.php
resources/views/dashboard/customer/pesanan/*
database/migrations/2026_05_12_110000_create_pesanans_table.php
database/migrations/2026_05_12_110001_create_detail_pesanans_table.php
database/migrations/2026_05_12_110002_create_form_pesanans_table.php
database/migrations/2026_05_12_110003_create_pembayarans_table.php
database/migrations/2026_05_12_110004_create_notifikasis_table.php
database/migrations/2026_05_17_* (status flow)
database/migrations/2026_05_18_* (jadwal, estimasi, enum, rebuild notif)
database/migrations/2026_05_22_070936_add_performance_indexes.php
```

### 5.4 Alur Kerja Fitur (State Machine 7 Status)

```
[MENUNGGU_KONFIRMASI_ADMIN]
  ↑ Customer checkout dari keranjang
  ↓ Menunggu admin konfirmasi

[MENUNGGU_PEMBAYARAN]
  ↑ Admin konfirmasi & minta pembayaran
  ↓ Customer upload bukti transfer

[MENUNGGU_VERIFIKASI_PEMBAYARAN]
  ↑ Customer upload bukti bayar
  ↓ Admin verifikasi bukti bayar

[DIKONFIRMASI]
  ↑ Admin verifikasi bukti bayar
  ↓ Admin mulai proses pengerjaan

[SEDANG_DIPROSES]
  ↑ Admin mulai kerjakan
  ↓ Admin selesaikan

[SELESAI] ← terminal state
  ↑ Admin tandai selesai

[DITOLAK] ← terminal state (dari menunggu_konfirmasi atau menunggu_pembayaran)
```

**Step Detail - Checkout (Web):**
```
Customer POST /pesanan/checkout
→ PesananController@checkout()
→ Validasi: nama_pemesan, alamat, no_hp, model_kendaraan, warna, lokasi, jadwal
→ Ambil keranjang aktif user
→ Hitung total_harga dari detail keranjang
→ Buat Pesanan (status: menunggu_konfirmasi_admin, kode: PSN-XXXXXXXX)
→ Copy detail_keranjang → detail_pesanan
→ Buat FormPesanan (data kendaraan, jadwal)
→ Dispatch OrderCreated event
→ Notifikasi admin via Filament
→ Update keranjang status → 'checked_out'
→ Redirect ke pesanan.show
```

**Step Detail - Upload Bukti (Web):**
```
Customer POST /pesanan/{id}/upload-bukti
→ PesananController@uploadBukti()
→ Validasi: metode_pembayaran, bukti_transfer (image, max 5MB)
→ Cek status == 'menunggu_pembayaran'
→ Store image ke storage/app/public/bukti_transfer
→ UpdateOrCreate Pembayaran
→ Update status pesanan → 'menunggu_verifikasi_pembayaran'
→ Dispatch PaymentUploaded event
→ Buat Notifikasi untuk user
→ Notifikasi admin via Filament
→ Redirect ke pesanan.show
```

**API Checkout (Sanctum):**
```
POST /api/pesanan
→ Api\PesananController@store()
→ CheckoutRequest validation
→ PesananService@checkout($userId, $data)
  → Lock keranjang (lockForUpdate)
  → Buat Pesanan dengan kode ORD-YYYYMMDDHHMMSS-XXXXXX
  → Buat DetailPesanan dari cart
  → Buat FormPesanan
  → Hapus cart
  → Dispatch OrderCreated event
→ Return PesananResource (201)
```

### 5.5 Database

**Tabel:** `pesanans`

| Kolom | Tipe | Keterangan |
|---|---|---|
| id_pesanan | bigint UNSIGNED AUTO_INCREMENT | PK |
| id_user | bigint UNSIGNED | FK → users.id |
| kode_pesanan | VARCHAR(255) | UNIQUE |
| tanggal_pesan | DATE | NOT NULL |
| status | VARCHAR(64) | DEFAULT 'menunggu_konfirmasi_admin' |
| catatan_admin | TEXT | NULLABLE |
| total_harga | DECIMAL(15,2) | DEFAULT 0 |

**Tabel:** `detail_pesanans`

| Kolom | Tipe | Keterangan |
|---|---|---|
| id_detail | bigint UNSIGNED AUTO_INCREMENT | PK |
| id_pesanan | bigint UNSIGNED | FK → pesanans.id_pesanan |
| id_paket | bigint UNSIGNED | FK → layanans.id_layanan |
| jumlah | INTEGER | DEFAULT 1 |
| catatan_custom | TEXT | NULLABLE |
| harga_satuan | DECIMAL(15,2) | NOT NULL |
| subtotal | DECIMAL(15,2) | NOT NULL |

**Tabel:** `form_pesanans`

| Kolom | Tipe | Keterangan |
|---|---|---|
| id_form | bigint UNSIGNED AUTO_INCREMENT | PK |
| id_pesanan | bigint UNSIGNED | FK → pesanans.id_pesanan |
| nama_pemesan, alamat_pengiriman, no_hp | VARCHAR/TEXT | Data customer |
| model_kendaraan, warna_kendaraan, nomor_polisi, tahun_produksi | VARCHAR | Data kendaraan |
| lokasi_pengerjaan (toko/rumah), jadwal_pengerjaan (DATETIME), estimasi_durasi | VARCHAR/DATETIME | Jadwal |

**Tabel:** `pembayarans`

| Kolom | Tipe | Keterangan |
|---|---|---|
| id_pembayaran | bigint UNSIGNED AUTO_INCREMENT | PK |
| id_pesanan | bigint UNSIGNED | FK → pesanans.id_pesanan |
| metode_pembayaran | VARCHAR(255) | transfer_bank, transfer_e_wallet, cash |
| jumlah_bayar | DECIMAL(15,2) | NOT NULL |
| bukti_transfer | VARCHAR(255) | NULLABLE (path) |
| status | ENUM('menunggu_pembayaran','sudah_dibayar','ditolak') | |
| verifikasi_admin | ENUM('menunggu','diverifikasi','ditolak') | |

**Relasi:**
```
Pesanan
  → User (belongsTo)
  → DetailPesanan (hasMany) → Layanan (belongsTo)
  → FormPesanan (hasOne)
  → Pembayaran (hasOne)
  → Notifikasi (hasMany)
```

**Migration:** 10+ file
**Performance Indexes:** `pesanans(id_user)`, `pesanans(status)`, `pesanans(tanggal_pesan)`, `pesanans(id_user, status)` composite

### 5.6 Keamanan

| Aspek | Implementasi |
|---|---|
| **Authentication** | Auth (web) + auth:sanctum (API) |
| **Authorization** | Policy: PesananPolicy (admin bisa semua, user hanya pesanan sendiri) |
| **Rate Limiting** | Web: throttle 60/5min; API: throttle 60/1min |
| **Upload Validation** | Image: jpg,jpeg,png,webp; max 5120 KB |
| **State Machine** | OrderStatus enum membatasi transisi status yang valid |
| **Event-Driven** | Semua perubahan status via event/listener, terpisah dari controller |
| **DB Transaction** | PesananService menggunakan DB transaction + lockForUpdate |
| **Ownership Check** | `where('id_user', Auth::id())` di setiap query pesanan milik user |

### 5.7 Integrasi dengan Fitur Lain

- **Keranjang** (checkout dari keranjang)
- **Checkout** (proses pembuatan pesanan)
- **Riwayat Pemesanan** (status pesanan muncul di riwayat)
- **Admin Pemesanan** (admin mengelola status pesanan)
- **Notifikasi** (setiap perubahan status menghasilkan notifikasi)
- **Pembayaran** (upload bukti bayar terkait pesanan)

### 5.8 Status Implementasi

**Lengkap** – Alur pemesanan 7 status + event + notifikasi berfungsi penuh.

---

## 6. KERANJANG

### 6.1 Tujuan Fitur

- **Fungsi bisnis:** Menampung pilihan layanan pelanggan sebelum checkout.
- **Kebutuhan pengguna:** Pelanggan dapat menambah, mengubah jumlah, dan menghapus paket di keranjang (max 3 item berbeda).

### 6.2 Lokasi Implementasi

| Komponen | Lokasi |
|---|---|
| **Route Web** | `routes/web.php:56-62` (prefix keranjang: index, tambah, update, hapus, kosongkan) |
| **Route Web API** | `routes/web.php:104-108` (prefix api/keranjang: index, tambah, update, hapus, kosongkan) |
| **Route API** | `routes/api.php:45-53` (prefix keranjang: index, count, check, addItem, updateItem, removeItem, clear) |
| **Controller Web** | `app/Http/Controllers/KeranjangController.php` |
| **Controller Web API** | `app/Http/Controllers/Api/KeranjangApiController.php` |
| **Controller API** | `app/Http/Controllers/Api/KeranjangController.php` |
| **Service** | `app/Services/KeranjangService.php` |
| **Service (legacy)** | `app/Services/CartService.php` |
| **Model** | `app/Models/Keranjang.php` |
| **Model** | `app/Models/DetailKeranjang.php` |
| **Request** | `app/Http/Requests/Keranjang/AddToCartRequest.php` |
| **Request** | `app/Http/Requests/Keranjang/UpdateCartItemRequest.php` |
| **Policy** | `app/Policy/KeranjangPolicy.php` |
| **Resource API** | `app/Http/Resources/KeranjangResource.php` |
| **Resource API** | `app/Http/Resources/DetailKeranjangResource.php` |
| **View** | `resources/views/dashboard/customer/keranjang/index.blade.php` |
| **View (partial)** | `resources/views/dashboard/customer/keranjang/_header.blade.php` |
| **View (partial)** | `resources/views/dashboard/customer/keranjang/_empty.blade.php` |
| **JS** | `resources/js/components/cart.js` | |
| **JS** | `resources/js/api.js` |
| **JS** | `resources/js/utils/*` (formatting, storage, ui) |

### 6.3 Struktur File

```
routes/web.php (baris 56-62)
routes/web.php (baris 104-108)
routes/api.php (baris 45-53)
app/Http/Controllers/KeranjangController.php
app/Http/Controllers/Api/KeranjangApiController.php
app/Http/Controllers/Api/KeranjangController.php
app/Services/KeranjangService.php
app/Services/CartService.php (legacy)
app/Models/Keranjang.php
app/Models/DetailKeranjang.php
app/Http/Requests/Keranjang/AddToCartRequest.php
app/Http/Requests/Keranjang/UpdateCartItemRequest.php
app/Policies/KeranjangPolicy.php
app/Http/Resources/KeranjangResource.php
app/Http/Resources/DetailKeranjangResource.php
resources/views/dashboard/customer/keranjang/*
resources/js/components/cart.js
resources/js/api.js
resources/js/utils/*
database/migrations/2026_05_12_100000_create_keranjangs_table.php
database/migrations/2026_05_12_100001_create_detail_keranjangs_table.php
```

### 6.4 Alur Kerja Fitur

**Tambah ke Keranjang (Web):**
```
Customer klik "Tambah ke Keranjang" di katalog
→ POST /keranjang/tambah {id_paket, jumlah, catatan_custom}
→ KeranjangController@tambah()
→ Validasi: id_paket exists, jumlah min 1, catatan max 500
→ firstOrCreate keranjang (id_user, status:active)
→ Cek max 3 item berbeda (jika sudah 3, return error)
→ Jika item sudah ada, increment jumlah
→ Jika baru, create DetailKeranjang
→ Redirect ke keranjang.index / checkout (jika direct_checkout)
```

**Tambah ke Keranjang (API Sanctum):**
```
POST /api/keranjang/item {id_layanan, quantity, custom_data}
→ Api\KeranjangController@addItem()
→ AddToCartRequest validation
→ KeranjangService@addItem($userId, $idLayanan, $quantity, $customData)
  → Max 3 items check
  → Jika existing, increment; else create new
  → DB transaction
→ Return KeranjangResource (201)
```

**API Cart JS (Web-based):**
```
resources/js/components/cart.js
  → Menggunakan fetch ke /api/keranjang (web API)
  → addToCart(packageId, quantity)
  → updateCartItem(detailId, quantity)
  → removeFromCart(detailId)
  → getCartCount()
  → Semua request dengan CSRF token
```

### 6.5 Database

**Tabel:** `keranjangs`

| Kolom | Tipe | Keterangan |
|---|---|---|
| id_keranjang | bigint UNSIGNED AUTO_INCREMENT | PK |
| id_user | bigint UNSIGNED | FK → users.id ON DELETE CASCADE |
| status | ENUM('active','checked_out') | DEFAULT 'active' |

**Tabel:** `detail_keranjangs`

| Kolom | Tipe | Keterangan |
|---|---|---|
| id_detail | bigint UNSIGNED AUTO_INCREMENT | PK |
| id_keranjang | bigint UNSIGNED | FK → keranjangs.id_keranjang ON DELETE CASCADE |
| id_paket | bigint UNSIGNED | FK → layanans.id_layanan ON DELETE CASCADE |
| jumlah | INTEGER | DEFAULT 1 |
| catatan_custom | TEXT | NULLABLE |
| harga_satuan | DECIMAL(15,2) | NOT NULL |
| subtotal | DECIMAL(15,2) | NOT NULL |

**Unique Index:** `detail_keranjangs(id_keranjang, id_paket)` (mencegah duplikat)
**Composite Index:** `keranjangs(id_user, status)`

**Relasi:**
```
Keranjang → User (belongsTo)
Keranjang → DetailKeranjang (hasMany)
DetailKeranjang → Layanan (belongsTo, via id_paket)
```

**Migration:** 2 file

### 6.6 Keamanan

| Aspek | Implementasi |
|---|---|
| **Authentication** | Auth (web) + auth:sanctum (API) |
| **Authorization** | Policy: KeranjangPolicy → hanya pemilik keranjang bisa akses |
| **Ownership Check** | `$detail->keranjang->id_user !== Auth::id()` → 403 |
| **Rate Limiting** | Web: throttle 60/5min; API: throttle 60/1min |
| **Max Items** | 3 item berbeda per keranjang (enforced di controller & service) |
| **Validation** | AddToCartRequest, UpdateCartItemRequest |

### 6.7 Integrasi dengan Fitur Lain

- **Katalog Layanan** (tambah ke keranjang dari katalog)
- **Checkout** (checkout dari keranjang → buat pesanan)
- **Direct Order** (langsung checkout tanpa keranjang)
- **Dashboard Pelanggan** (ringkasan keranjang)

### 6.8 Status Implementasi

**Lengkap** – Keranjang dengan fitur CRUD, max 3 items, API web + Sanctum, JS async component.

---

## 7. CHECKOUT

### 7.1 Tujuan Fitur

- **Fungsi bisnis:** Form pengisian data pemesanan sebelum pesanan dibuat.
- **Kebutuhan pengguna:** Pelanggan mengisi data diri, data kendaraan, dan jadwal pengerjaan.

### 7.2 Lokasi Implementasi

| Komponen | Lokasi |
|---|---|
| **Route Web** | `routes/web.php:81-92` (GET /pesanan/checkout, POST /pesanan/checkout) |
| **Route Web** | `routes/web.php:69-79` (GET /pesanan/buat → direct order) |
| **Controller** | `app/Http/Controllers/PesananController.php` (checkout method) |
| **Service** | `app/Services/PesananService.php` (checkout logic) |
| **Request** | `app/Http/Requests/Pesanan/CheckoutRequest.php` |
| **Event** | `app/Events/OrderCreated.php` |
| **View** | `resources/views/dashboard/customer/pesanan/checkout.blade.php` |
| **View** | `resources/views/dashboard/customer/pesanan/direct-order.blade.php` |

### 7.3 Struktur File

```
routes/web.php (baris 69-79: direct order, baris 81-92: checkout)
app/Http/Controllers/PesananController.php -> checkout()
app/Services/PesananService.php -> checkout()
app/Http/Requests/Pesanan/CheckoutRequest.php
app/Events/OrderCreated.php
resources/views/dashboard/customer/pesanan/checkout.blade.php
resources/views/dashboard/customer/pesanan/direct-order.blade.php
```

### 7.4 Alur Kerja Fitur

**Checkout dari Keranjang:**
```
Customer klik "Checkout" di halaman keranjang
→ GET /pesanan/checkout
→ Closure di routes: ambil keranjang aktif user
→ Jika kosong, redirect ke katalog dengan error
→ Tampilkan view checkout dengan data keranjang

Customer submit form
→ POST /pesanan/checkout
→ PesananController@checkout()
→ Validasi: nama_pemesan, alamat_pengiriman, no_hp, model_kendaraan,
  warna_kendaraan, lokasi_pengerjaan, jadwal_pengerjaan, keterangan_tambahan
→ Ambil keranjang aktif user + details.layanan
→ Hitung total_harga
→ Buat Pesanan (status: menunggu_konfirmasi_admin, kode: PSN-random8)
→ Copy detail_keranjang → DetailPesanan
→ Buat FormPesanan (data kendaraan + jadwal + estimasi "4-5 Hari Kerja")
→ Dispatch OrderCreated event
→ Kirim notifikasi admin via Filament
→ Update status keranjang → 'checked_out'
→ Redirect ke pesanan.show dengan toast_success
```

**Direct Order (Pesan Langsung dari Katalog):**
```
Customer klik "Pesan Sekarang" di kartu layanan
→ GET /pesanan/buat?package_id=X
→ Closure: ambil Layanan by ID
→ Tampilkan view direct-order dengan data package

Customer submit form
→ POST /pesanan/checkout (sama dengan checkout biasa)
→ Bedanya: keranjang dibuat sementara dari package tersebut
```

### 7.5 Database

Tabel yang terlibat: `pesanans`, `detail_pesanans`, `form_pesanans`, `keranjangs` (update status)

### 7.6 Keamanan

| Aspek | Implementasi |
|---|---|
| **Authentication** | Auth (web) |
| **Authorization** | role:admin\|user |
| **Rate Limiting** | throttle:60,5 |
| **Validation** | Validasi lengkap: nama (100), alamat (required), no_hp (max 20), kendaraan (100), jadwal (date), dll |

### 7.7 Integrasi dengan Fitur Lain

- **Keranjang** (checkout dari keranjang)
- **Katalog** (direct order dari katalog)
- **Pemesanan** (setelah checkout → menunggu konfirmasi admin)
- **Notifikasi** (OrderCreated event → notifikasi admin + email customer)

### 7.8 Status Implementasi

**Lengkap** – Checkout dari keranjang + direct order berfungsi penuh.

---

## 8. RIWAYAT PEMESANAN

### 8.1 Tujuan Fitur

- **Fungsi bisnis:** Menampilkan daftar pesanan yang sudah dibuat pelanggan beserta statusnya.
- **Kebutuhan pengguna:** Pelanggan dapat melihat riwayat pesanan, filter status, dan melihat detail invoice.

### 8.2 Lokasi Implementasi

| Komponen | Lokasi |
|---|---|
| **Route Web** | `routes/web.php:66` (GET /pesanan) |
| **Route Web** | `routes/web.php:93` (GET /pesanan/{id}) |
| **Route Web** | `routes/web.php:94` (GET /pesanan/{id}/invoice) |
| **Controller** | `app/Http/Controllers/PesananController.php` (index, show, invoice) |
| **Controller API Web** | `app/Http/Controllers/Api/PesananApiController.php` (index, show, status, timeline) |
| **Controller API** | `app/Http/Controllers/Api/PesananController.php` (index, show, getInvoice) |
| **Service** | `app/Services/PesananService.php` (getUserOrders, getOrderDetails) |
| **Model** | `app/Models/Pesanan.php` |
| **View** | `resources/views/dashboard/customer/pesanan/index.blade.php` |
| **View** | `resources/views/dashboard/customer/pesanan/show.blade.php` |
| **View** | `resources/views/dashboard/customer/pesanan/invoice.blade.php` |

### 8.3 Struktur File

```
routes/web.php (baris 66, 93, 94)
app/Http/Controllers/PesananController.php -> index(), show(), invoice()
app/Http/Controllers/Api/PesananApiController.php -> index(), show(), status(), timeline()
app/Http/Controllers/Api/PesananController.php -> index(), show(), getInvoice()
app/Services/PesananService.php
app/Models/Pesanan.php
resources/views/dashboard/customer/pesanan/index.blade.php
resources/views/dashboard/customer/pesanan/show.blade.php
resources/views/dashboard/customer/pesanan/invoice.blade.php
```

### 8.4 Alur Kerja Fitur

**Daftar Pesanan:**
```
Customer GET /pesanan?status=menunggu_pembayaran|berjalan|selesai
→ PesananController@index()
→ Query: Pesanan::where('id_user', Auth::id())->with(['form','pembayaran','details.layanan'])
→ Filter status:
  - menunggu_pembayaran: menunggu_konfirmasi_admin, menunggu_pembayaran, menunggu_verifikasi_pembayaran
  - berjalan: dikonfirmasi, sedang_diproses
  - selesai: selesai
  - default (riwayat): dikonfirmasi, sedang_diproses, selesai, ditolak
→ Return view dengan data pesanans
```

**Detail Pesanan:**
```
Customer GET /pesanan/{id}
→ PesananController@show()
→ Pesanan::where('id_user', Auth::id())->where('id_pesanan', $id)->firstOrFail()
→ with('details.layanan', 'form', 'pembayaran')
→ Return view dengan data pesanan
```

**Invoice:**
```
Customer GET /pesanan/{id}/invoice
→ PesananController@invoice()
→ Cek bisaUnduhInvoice() (status: dikonfirmasi, sedang_diproses, selesai)
→ Admin bisa akses semua invoice (hasRole('admin'))
→ Return view invoice dengan data pesanan
```

**API Timeline:**
```
GET /api/pesanan/{id}/timeline
→ PesananApiController@timeline()
→ Return array:
  - menunggu_verifikasi: completed=true jika status bukan menunggu_konfirmasi_admin
  - diverifikasi: completed=true jika status sudah melewati menunggu_konfirmasi_admin
  - menunggu_pembayaran: completed=true jika status sudah melewati menunggu_pembayaran
  - dibayar: completed=true jika status sudah melewati menunggu_verifikasi_pembayaran
  - selesai: completed=true jika status == selesai
```

### 8.5 Database

Tabel `pesanans` (sudah dijelaskan di fitur Pemesanan)

### 8.6 Keamanan

| Aspek | Implementasi |
|---|---|
| **Authentication** | Auth (web) |
| **Authorization** | role:admin\|user; Ownership check where('id_user', Auth::id()) |
| **Rate Limiting** | throttle:60,5 |
| **Policy** | PesananPolicy: view → admin atau pemilik pesanan |

### 8.7 Integrasi dengan Fitur Lain

- **Pemesanan** (data berasal dari pemesanan)
- **Invoice** (link ke invoice dari daftar pesanan)
- **Upload Bukti** (upload bukti dari halaman detail pesanan)

### 8.8 Status Implementasi

**Lengkap** – Daftar pesanan dengan filter, detail, invoice, timeline API berfungsi penuh.

---

## 9. ADMIN PROFIL PERUSAHAAN

### 9.1 Tujuan Fitur

- **Fungsi bisnis:** Admin dapat mengedit konten halaman profil perusahaan, beranda, tentang kami, layanan, galeri, dan katalog melalui panel Filament.
- **Kebutuhan admin:** Mengelola semua konten website tanpa mengubah kode.

### 9.2 Lokasi Implementasi

| Komponen | Lokasi |
|---|---|
| **Resource Filament** | `app/Filament/Resources/BerandaResource.php` (edit halaman beranda) |
| **Resource Filament** | `app/Filament/Resources/TentangKamiResource.php` (edit tentang kami) |
| **Resource Filament** | `app/Filament/Resources/HalamanLayananResource.php` (edit halaman layanan) |
| **Resource Filament** | `app/Filament/Resources/HalamanGaleriResource.php` (edit halaman galeri) |
| **Page Filament** | `app/Filament/Resources/Berandas/Pages/EditBeranda.php` |
| **Page Filament** | `app/Filament/Resources/TentangKamis/Pages/EditTentangKami.php` |
| **Page Filament** | `app/Filament/Resources/HalamanLayanan/Pages/EditHalamanLayanan.php` |
| **Page Filament** | `app/Filament/Resources/HalamanGaleri/Pages/EditHalamanGaleri.php` |
| **Model** | `app/Models/ProfilPerusahaan.php` (semua resource ini menggunakan model yang sama) |
| **Filament Widget** | `app/Filament/Widgets/OrderStatsWidget.php` |
| **Filament Widget** | `app/Filament/Widgets/RecentOrdersWidget.php` |
| **Filament Widget** | `app/Filament/Widgets/RevenueChartWidget.php` |

### 9.3 Struktur File

```
# Beranda
app/Filament/Resources/BerandaResource.php
app/Filament/Resources/Berandas/Pages/EditBeranda.php

# Tentang Kami
app/Filament/Resources/TentangKamiResource.php
app/Filament/Resources/TentangKamis/Pages/EditTentangKami.php

# Halaman Layanan
app/Filament/Resources/HalamanLayananResource.php
app/Filament/Resources/HalamanLayanan/Pages/EditHalamanLayanan.php

# Halaman Galeri
app/Filament/Resources/HalamanGaleriResource.php
app/Filament/Resources/HalamanGaleri/Pages/EditHalamanGaleri.php

# Widget
app/Filament/Widgets/OrderStatsWidget.php
app/Filament/Widgets/RecentOrdersWidget.php
app/Filament/Widgets/RevenueChartWidget.php

# Model (shared)
app/Models/ProfilPerusahaan.php
```

### 9.4 Alur Kerja Fitur

```
Admin login ke /admin (Filament)
→ Melihat dashboard dengan widget (OrderStats, RecentOrders, RevenueChart)
→ Navigasi ke "Halaman Website" → "Edit Beranda"
→ BerandaResource (model: ProfilPerusahaan, singleton)
→ EditBeranda page menampilkan form dengan fields:
  - Hero: home_badge_text, home_hero_title_line1/2, home_subtitle
  - Statistics: home_stat1/2_value, home_stat1/2_label
  - Keunggulan: home_keunggulan_card1_title/desc ~ card4
  - Steps: home_step1_title/desc ~ step3
  - CTA: home_cta_title, home_cta_subtitle
→ Admin save → ProfilPerusahaan::first()->update(...)
→ Cache 'site_profile' di-flush (via boot events)
→ Website langsung update
```

**Pattern Singleton:** Setiap resource menggunakan pola singleton:
```php
// Di EditBeranda.php:
ProfilPerusahaan::firstOrCreate(
    ['id' => 1],
    ['nama_perusahaan' => 'Dantie Sticker', ...]
);
```

### 9.5 Database

**Tabel:** `profil_perusahaans` (satu record, id=1) dengan 100+ kolom CMS yang dikelompokkan per halaman.

**CMS Fields yang dikelola per resource:**

| Resource | Prefix Kolom | Jumlah Field |
|---|---|---|
| BerandaResource | `home_` (hero, stats, keunggulan, steps, CTA) | ~25 field |
| TentangKamiResource | `tentang_kami_`, `visi`, `misi`, `sejarah` | ~15 field |
| HalamanLayananResource | `layanan_hero_*` | 3 field |
| HalamanGaleriResource | `galeri_hero_*` | 3 field |

### 9.6 Keamanan

| Aspek | Implementasi |
|---|---|
| **Authentication** | Filament built-in auth |
| **Authorization** | User::canAccessPanel() → hanya role 'admin' |
| **Route** | /admin (Filament prefix, fully protected) |

### 9.7 Integrasi dengan Fitur Lain

- **Profil Perusahaan** (data dari CMS ditampilkan di halaman publik)
- **Beranda** (hero, stats, keunggulan, steps dari CMS)
- **Tentang Kami** (hero, visi, misi, sejarah dari CMS)
- **Layanan** (hero dari CMS)
- **Galeri** (hero dari CMS)

### 9.8 Status Implementasi

**Lengkap** – 4 resource singleton untuk mengelola konten website via Filament.

---

## 10. ADMIN GALERI

### 10.1 Tujuan Fitur

- **Fungsi bisnis:** Admin dapat mengelola data galeri hasil pekerjaan (CRUD).
- **Kebutuhan admin:** Menambah, mengedit, menghapus entri galeri dengan foto.

### 10.2 Lokasi Implementasi

| Komponen | Lokasi |
|---|---|
| **Resource Filament** | `app/Filament/Resources/Galeris/GaleriResource.php` |
| **Schema** | `app/Filament/Resources/Galeris/Schemas/GaleriForm.php` |
| **Table** | `app/Filament/Resources/Galeris/Tables/GalerisTable.php` |
| **Page** | `app/Filament/Resources/Galeris/Pages/ListGaleris.php` |
| **Page** | `app/Filament/Resources/Galeris/Pages/CreateGaleri.php` |
| **Page** | `app/Filament/Resources/Galeris/Pages/EditGaleri.php` |
| **Model** | `app/Models/Galeri.php` |

### 10.3 Struktur File

```
app/Filament/Resources/Galeris/GaleriResource.php
app/Filament/Resources/Galeris/Schemas/GaleriForm.php
app/Filament/Resources/Galeris/Tables/GalerisTable.php
app/Filament/Resources/Galeris/Pages/ListGaleris.php
app/Filament/Resources/Galeris/Pages/CreateGaleri.php
app/Filament/Resources/Galeris/Pages/EditGaleri.php
app/Models/Galeri.php
```

### 10.4 Form Fields (GaleriForm)

| Field | Tipe | Validasi |
|---|---|---|
| judul | TextInput | required, max 255 |
| sub_judul | TextInput | max 255 |
| deskripsi | Textarea | rows 5 |
| foto | FileUpload | image, required, max 10MB, dir: galeri |
| kategori | TextInput | - |
| badge_text | TextInput | - |
| tanggal_upload | DatePicker | required |
| is_featured | Toggle | default false |

### 10.5 Table Columns (GalerisTable)

| Column | Tipe |
|---|---|
| foto | ImageColumn (size 80) |
| judul | TextColumn (searchable, sortable) |
| kategori | TextColumn (badge) |
| tanggal_upload | TextColumn (date) |
| is_featured | BadgeColumn (Ya/Tidak) |

**Actions:** EditAction, DeleteAction
**Filter:** TernaryFilter is_featured

### 10.6 Status Implementasi

**Lengkap** – CRUD galeri via Filament dengan upload foto.

---

## 11. ADMIN KATALOG

### 11.1 Tujuan Fitur

- **Fungsi bisnis:** Admin dapat mengelola data layanan/katalog (CRUD + View).
- **Kebutuhan admin:** Menambah, mengedit, menghapus, dan melihat detail layanan.

### 11.2 Lokasi Implementasi

| Komponen | Lokasi |
|---|---|
| **Resource Filament** | `app/Filament/Resources/Layanans/LayananResource.php` |
| **Schema** | `app/Filament/Resources/Layanans/Schemas/LayananForm.php` |
| **Schema** | `app/Filament/Resources/Layanans/Schemas/LayananInfolist.php` |
| **Table** | `app/Filament/Resources/Layanans/Tables/LayanansTable.php` |
| **Page** | `app/Filament/Resources/Layanans/Pages/ListLayanans.php` |
| **Page** | `app/Filament/Resources/Layanans/Pages/CreateLayanan.php` |
| **Page** | `app/Filament/Resources/Layanans/Pages/EditLayanan.php` |
| **Page** | `app/Filament/Resources/Layanans/Pages/ViewLayanan.php` |
| **Model** | `app/Models/Layanan.php` |

### 11.3 Struktur File

```
app/Filament/Resources/Layanans/LayananResource.php
app/Filament/Resources/Layanans/Schemas/LayananForm.php
app/Filament/Resources/Layanans/Schemas/LayananInfolist.php
app/Filament/Resources/Layanans/Tables/LayanansTable.php
app/Filament/Resources/Layanans/Pages/ListLayanans.php
app/Filament/Resources/Layanans/Pages/CreateLayanan.php
app/Filament/Resources/Layanans/Pages/EditLayanan.php
app/Filament/Resources/Layanans/Pages/ViewLayanan.php
app/Models/Layanan.php
```

### 11.4 Form Fields (LayananForm)

| Field | Tipe | Validasi |
|---|---|---|
| foto_contoh | FileUpload | image, dir: layanan/admin |
| nama_layanan | TextInput | required, max 255 |
| kategori | Select | mobil/motor/stiker |
| tipe_layanan | Select | fix/custom (live) |
| harga | TextInput | numeric, visible only if fix |
| estimasi_waktu | TextInput | - |
| deskripsi | RichEditor | limited toolbar |
| fitur | Repeater of TextInput | min 1 item |

### 11.5 Table Columns (LayanansTable)

| Column | Tipe |
|---|---|
| id_layanan | TextColumn (sortable) |
| nama_layanan | TextColumn (searchable) |
| tipe_layanan | BadgeColumn (fix=success, custom=warning) |
| harga | TextColumn (money IDR, sortable) |
| estimasi_waktu | TextColumn |
| created_at | TextColumn (date) |

**Actions:** EditAction, DeleteAction
**Filter:** SelectFilter tipe_layanan

### 11.6 Status Implementasi

**Lengkap** – CRUD + View layanan via Filament.

---

## 12. ADMIN PEMESANAN

### 12.1 Tujuan Fitur

- **Fungsi bisnis:** Admin dapat mengelola seluruh pesanan pelanggan (CRUD + workflow status).
- **Kebutuhan admin:** Melihat semua pesanan, mengubah status, menambahkan catatan, verifikasi pembayaran.

### 12.2 Lokasi Implementasi

| Komponen | Lokasi |
|---|---|
| **Route API** | `routes/api.php:82-90` (admin/pesanan: index, show, updateStatus, addNote) |
| **Controller API** | `app/Http/Controllers/Api/Admin/AdminPesananController.php` |
| **Resource Filament** | `app/Filament/Resources/Pesanans/Pesanans/PesananResource.php` |
| **Schema** | `app/Filament/Resources/Pesanans/Pesanans/Schemas/PesananForm.php` |
| **Schema** | `app/Filament/Resources/Pesanans/Pesanans/Schemas/PesananInfolist.php` |
| **Table** | `app/Filament/Resources/Pesanans/Pesanans/Tables/PesanansTable.php` |
| **Page** | `app/Filament/Resources/Pesanans/Pesanans/Pages/ListPesanans.php` |
| **Page** | `app/Filament/Resources/Pesanans/Pesanans/Pages/CreatePesanan.php` |
| **Page** | `app/Filament/Resources/Pesanans/Pesanans/Pages/EditPesanan.php` |
| **Page** | `app/Filament/Resources/Pesanans/Pesanans/Pages/ViewPesanan.php` |
| **Service** | `app/Services/PesananService.php` (updateStatus) |
| **Request** | `app/Http/Requests/Admin/Pesanan/UpdateOrderStatusRequest.php` |
| **Model** | `app/Models/Pesanan.php` |
| **Filament Page (non-resource)** | `app/Filament/Pages/LaporanPenjualan.php` |
| **Filament Widget** | `app/Filament/Widgets/OrderStatsWidget.php` |
| **Filament Widget** | `app/Filament/Widgets/RecentOrdersWidget.php` |
| **Filament Widget** | `app/Filament/Widgets/RevenueChartWidget.php` |
| **View** | `resources/views/filament/pages/laporan-penjualan.blade.php` |

### 12.3 Struktur File

```
# Filament Resource
app/Filament/Resources/Pesanans/Pesanans/PesananResource.php
app/Filament/Resources/Pesanans/Pesanans/Schemas/PesananForm.php
app/Filament/Resources/Pesanans/Pesanans/Schemas/PesananInfolist.php
app/Filament/Resources/Pesanans/Pesanans/Tables/PesanansTable.php
app/Filament/Resources/Pesanans/Pesanans/Pages/ListPesanans.php
app/Filament/Resources/Pesanans/Pesanans/Pages/CreatePesanan.php
app/Filament/Resources/Pesanans/Pesanans/Pages/EditPesanan.php
app/Filament/Resources/Pesanans/Pesanans/Pages/ViewPesanan.php

# API Admin
app/Http/Controllers/Api/Admin/AdminPesananController.php

# Laporan & Widgets
app/Filament/Pages/LaporanPenjualan.php
app/Filament/Widgets/OrderStatsWidget.php
app/Filament/Widgets/RecentOrdersWidget.php
app/Filament/Widgets/RevenueChartWidget.php
resources/views/filament/pages/laporan-penjualan.blade.php

# Service & Request
app/Services/PesananService.php
app/Http/Requests/Admin/Pesanan/UpdateOrderStatusRequest.php

# Model
app/Models/Pesanan.php
```

### 12.4 Workflow Actions (PesananResource Table Actions)

| Action | Status Sebelum | Status Sesudah |
|---|---|---|
| konfirmasiPesanan | menunggu_konfirmasi_admin | menunggu_pembayaran |
| verifikasiPembayaran | menunggu_verifikasi_pembayaran | dikonfirmasi |
| mulaiProses | dikonfirmasi | sedang_diproses |
| selesaikan | sedang_diproses | selesai |
| tolakPesanan | (any cancellable) | ditolak |

**Tabs di ListPesanans:**
- Semua Pesanan
- Perlu Verifikasi
- Menunggu Bayar
- Validasi Bayar
- Dalam Proses
- Selesai

**Admin API (Sanctum + role:admin):**
```php
GET    /api/admin/pesanan           # List all orders (filterable by status, user_id)
GET    /api/admin/pesanan/{id}      # Detail pesanan
PUT    /api/admin/pesanan/{id}/status  # Update status (via PesananService)
POST   /api/admin/pesanan/{id}/note    # Add catatan_admin
```

### 12.5 Status Implementasi

**Lengkap** – Admin dapat mengelola pesanan via Filament (dengan rich workflow actions) + API.

---

## 13. ADMIN KERANJANG

### 13.1 Status

**Tidak ada implementasi khusus untuk admin keranjang.**

Tidak terdapat Filament resource, route, atau controller yang secara khusus mengelola keranjang dari sisi admin. Keranjang bersifat milik user dan hanya dikelola oleh user pemiliknya. Admin tidak perlu mengelola keranjang pelanggan.

**Penjelasan:**
- Policy `KeranjangPolicy` membatasi akses hanya untuk pemilik keranjang
- Tidak ada resource Filament untuk keranjang
- Tidak ada route admin untuk keranjang

### 13.2 Status Implementasi

**Tidak Diimplementasikan** (tidak diperlukan – keranjang adalah fitur user-side).

---

## 14. ADMIN RIWAYAT PEMESANAN

### 14.1 Tujuan Fitur

- **Fungsi bisnis:** Admin dapat melihat riwayat transaksi yang sudah selesai/ditolak.
- **Kebutuhan admin:** Memonitor pesanan yang sudah completed.

### 14.2 Lokasi Implementasi

| Komponen | Lokasi |
|---|---|
| **Resource Filament** | `app/Filament/Resources/RiwayatPesanans/RiwayatPesananResource.php` |
| **Page** | `app/Filament/Resources/RiwayatPesanans/Pages/ListRiwayatPesanans.php` |
| **Model** | `app/Models/Pesanan.php` |

### 14.3 Struktur File

```
app/Filament/Resources/RiwayatPesanans/RiwayatPesananResource.php
app/Filament/Resources/RiwayatPesanans/Pages/ListRiwayatPesanans.php
```

### 14.4 Detail

**Model:** `Pesanan` (sama dengan Admin Pemesanan)
**Tipe:** Read-only list (canCreate: false, canEdit: false, canDelete: false)
**Table Columns:** kode_pesanan, user.name, total_harga (money IDR), status (badge: selesai/dibayar=success, dibatalkan=danger), created_at
**Filter:** SelectFilter status (selesai, dibayar, dibatalkan)
**Action:** cetakInvoice (link ke route invoice, new tab)

### 14.5 Status Implementasi

**Lengkap** – Read-only list riwayat transaksi via Filament.

---

## 15. ADMIN VERIFIKASI PEMBAYARAN DAN KOREKSI HARGA

### 15.1 Tujuan Fitur

- **Fungsi bisnis:** Admin dapat memverifikasi atau menolak bukti pembayaran yang diupload pelanggan, serta mengelola data pembayaran.
- **Kebutuhan admin:** Melihat daftar pembayaran pending, verifikasi/reject bukti transfer.

### 15.2 Lokasi Implementasi

| Komponen | Lokasi |
|---|---|
| **Route API** | `routes/api.php:93-97` (admin/pembayaran: index, verify, reject) |
| **Controller API** | `app/Http/Controllers/Api/Admin/AdminPembayaranController.php` |
| **Service** | `app/Services/PembayaranService.php` (verifyPayment, rejectPayment) |
| **Model** | `app/Models/Pembayaran.php` |
| **Model** | `app/Models/Pesanan.php` |
| **Resource API** | `app/Http/Resources/PembayaranResource.php` |
| **Route Admin Web** | `routes/web.php:52` (GET /admin/laporan) |
| **Controller** | `app/Http/Controllers/LaporanController.php` |
| **View** | `resources/views/dashboard/admin/laporan/print.blade.php` |
| **Filament Page** | `app/Filament/Pages/LaporanPenjualan.php` |

### 15.3 Struktur File

```
# API Admin
app/Http/Controllers/Api/Admin/AdminPembayaranController.php
  -> index()
  -> verify(Pembayaran $pembayaran)
  -> reject(Request $request, Pembayaran $pembayaran)

# Service
app/Services/PembayaranService.php
  -> verifyPayment(Pembayaran, ?string $catatan)
  -> rejectPayment(Pembayaran, string $alasan)

# Model
app/Models/Pembayaran.php
app/Models/Pesanan.php

# Laporan
app/Http/Controllers/LaporanController.php
app/Filament/Pages/LaporanPenjualan.php
resources/views/dashboard/admin/laporan/print.blade.php
resources/views/filament/pages/laporan-penjualan.blade.php

# Enums
app/Enums/PaymentStatus.php (pending, verified, rejected, expired)
app/Enums/PaymentMethod.php (transfer_bank, transfer_e_wallet, cash)
```

### 15.4 Alur Kerja Fitur

**Verifikasi Pembayaran (Filament):**
```
Admin buka Kelola Pesanan → klik Pesanan
→ Di table action, klik "Verifikasi Pembayaran"
→ System call: PesananService@updateStatus()
  → PembayaranService@verifyPayment()
    → Lock pembayaran (lockForUpdate)
    → Update Pembayaran: status → VERIFIED
    → Update Pesanan: status → SEDANG_DIPROSES
    → Dispatch PaymentVerified event
    → Listener: NotifyOrderProcessingStarted (email ke customer)
```

**Verifikasi Pembayaran (API):**
```
PUT /api/admin/pembayaran/{pembayaran}/verify
→ AdminPembayaranController@verify()
→ Route-model binding Pembayaran
→ PembayaranService@verifyPayment($pembayaran)
  → Cek status == 'pending'
  → DB transaction + lockForUpdate
  → Update pembayaran: status = verified
  → Update pesanan: status = sedang_diproses
  → Dispatch PaymentVerified event
→ Return PembayaranResource
```

**Reject Pembayaran:**
```
PUT /api/admin/pembayaran/{pembayaran}/reject {alasan: required}
→ AdminPembayaranController@reject()
→ Validasi: alasan required, max 500
→ PembayaranService@rejectPayment($pembayaran, $alasan)
  → Update pembayaran: status = rejected, catatan_admin = alasan
  → Update pesanan: status kembali ke menunggu_pembayaran
→ Dispatch OrderRejected event (jika final) / tidak ada event (bisa upload ulang)
```

**Laporan Penjualan (Filament Page):**
```
Admin buka menu Laporan Penjualan
→ LaporanPenjualan page dengan header actions:
  - Cetak Harian → /admin/laporan?type=hari
  - Cetak Mingguan → /admin/laporan?type=minggu
  - Cetak Bulanan → /admin/laporan?type=bulan
→ LaporanController@index()
  → Filter Pesanan by status (dibayar, selesai)
  → Filter date: hari/minggu/bulan
  → Sum total_harga → totalPendapatan
  → Return view admin.laporan.print (untuk print)
```

### 15.5 Database

**Tabel:** `pembayarans`

| Kolom | Tipe | Keterangan |
|---|---|---|
| id_pembayaran | bigint UNSIGNED AUTO_INCREMENT | PK |
| id_pesanan | bigint UNSIGNED | FK → pesanans.id_pesanan |
| metode_pembayaran | VARCHAR(255) | transfer_bank, transfer_e_wallet, cash |
| jumlah_bayar | DECIMAL(15,2) | Jumlah yang dibayar |
| bukti_transfer | VARCHAR(255) | NULLABLE, path file |
| status | ENUM('menunggu_pembayaran','sudah_dibayar','ditolak') | |
| tgl_bayar | DATE | NULLABLE |
| verifikasi_admin | ENUM('menunggu','diverifikasi','ditolak') | DEFAULT 'menunggu' |
| catatan_admin | TEXT | NULLABLE |

**Indexes:** `status`, `(status, updated_at)` composite

### 15.6 Keamanan

| Aspek | Implementasi |
|---|---|
| **Authentication** | auth:sanctum (API) |
| **Authorization** | role:admin (middleware + route group) |
| **Rate Limiting** | throttle via API |
| **Route-Model Binding** | Pembayaran model binding |
| **Pessimistic Locking** | lockForUpdate() saat verifikasi |
| **Validation** | alasan required saat reject (max 500) |

### 15.7 Integrasi dengan Fitur Lain

- **Pemesanan** (status pesanan berubah saat pembayaran diverifikasi/ditolak)
- **Event/Listener** (PaymentVerified → email ke customer; reject → pesanan kembali ke menunggu_pembayaran)
- **Laporan Penjualan** (data pembayaran terverifikasi masuk ke laporan)

### 15.8 Status Implementasi

**Lengkap** – Verifikasi & reject pembayaran via Filament + API + laporan penjualan.

---

## REKAP STATUS IMPLEMENTASI

| No | Fitur | Status | Keterangan |
|---|---|---|---|
| 1 | Sistem Autentikasi dan Akun | ✅ Lengkap | Web (Breeze) + API (Sanctum) + Role/Policy |
| 2 | Profil Perusahaan | ✅ Lengkap | Halaman publik + data dari database singleton |
| 3 | Galeri Hasil Pekerjaan | ✅ Lengkap | Halaman publik + filter kategori |
| 4 | Katalog Layanan | ✅ Lengkap | Halaman publik + API + filter/search |
| 5 | Pemesanan | ✅ Lengkap | 7 status + event/listener + notifikasi |
| 6 | Keranjang | ✅ Lengkap | CRUD + max 3 items + API + JS async |
| 7 | Checkout | ✅ Lengkap | Dari keranjang + direct order |
| 8 | Riwayat Pemesanan | ✅ Lengkap | Filter status + detail + invoice + timeline API |
| 9 | Admin Profil Perusahaan | ✅ Lengkap | 4 resource singleton (Beranda, Tentang Kami, Halaman Layanan, Halaman Galeri) |
| 10 | Admin Galeri | ✅ Lengkap | CRUD Filament dengan upload foto |
| 11 | Admin Katalog | ✅ Lengkap | CRUD + View Filament |
| 12 | Admin Pemesanan | ✅ Lengkap | Resource Filament + workflow actions + API |
| 13 | Admin Keranjang | ❌ Tidak Ada | Tidak diimplementasikan (tidak diperlukan) |
| 14 | Admin Riwayat Pemesanan | ✅ Lengkap | Read-only list Filament |
| 15 | Admin Verifikasi Pembayaran & Koreksi Harga | ✅ Lengkap | API verify/reject + laporan penjualan |

---

## PENANGGUNG JAWAB FITUR

Berdasarkan analisis git history dan struktur kode:

| Fitur | Penanggung Jawab Utama |
|---|---|
| 1. Sistem Autentikasi & Akun | **Ahmad Septa** (inisialisasi Breeze), **Yogi Maulana** (API Auth, UserProfile) |
| 2. Profil Perusahaan | **Hillmi Nazwar** (init backend), **Yogi Maulana** (frontend view, maps) |
| 3. Galeri Hasil Pekerjaan | **Hillmi Nazwar** (init fitur), **Yogi Maulana** (cache + frontend) |
| 4. Katalog Layanan | **Ahmad Septa** (init Filament resource), **Yogi Maulana** (API + frontend) |
| 5. Pemesanan | **Yogi Maulana** (full: 7 status, event, controller) |
| 6. Keranjang | **Ahmad Septa** (backend model/migration), **Yogi Maulana** (full-stack) |
| 7. Checkout | **Yogi Maulana** (full implementasi) |
| 8. Riwayat Pemesanan | **Hillmi Nazwar** (resource Filament) |
| 9. Admin Profil Perusahaan | **Hillmi Nazwar** (resource awal), **Vero** (HalamanLayanan, HalamanGaleri) |
| 10. Admin Galeri | **Hillmi Nazwar** (init resource), **Yogi Maulana** (fix + cache) |
| 11. Admin Katalog | **Ahmad Septa** (init resource), **Vero** (Filament v5 fixes) |
| 12. Admin Pemesanan | **Hillmi Nazwar** (resource + widgets + actions) |
| 13. Admin Keranjang | – (tidak diimplementasikan) |
| 14. Admin Riwayat Pemesanan | **Hillmi Nazwar** |
| 15. Admin Verifikasi Pembayaran | **Yogi Maulana** (API + service), **Hillmi Nazwar** (widgets) |
