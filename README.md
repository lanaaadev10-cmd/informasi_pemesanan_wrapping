# 🚗 Dantie Wrapping — Sistem Informasi Pemesanan Wrapping

> **Sistem manajemen pemesanan jasa wrapping/stiker kendaraan** berbasis web.
> Dibangun dengan Laravel 12 + Filament 5 + Tailwind CSS.

---

## 📋 Daftar Isi

- [Tentang Aplikasi](#tentang-aplikasi)
- [Stack Teknologi](#stack-teknologi)
- [Fitur Utama](#fitur-utama)
- [Arsitektur Sistem](#arsitektur-sistem)
- [Struktur Folder](#struktur-folder)
- [Database & Migrasi](#database--migrasi)
- [Alur Status Pesanan](#alur-status-pesanan)
- [Hak Akses (Role & Permission)](#hak-akses-role--permission)
- [API Endpoints](#api-endpoints)
- [Filament Admin Panel](#filament-admin-panel)
- [Komponen Kunci](#komponen-kunci)
- [Cara Instalasi](#cara-instalasi)
- [Pengembangan](#pengembangan)

---

## Tentang Aplikasi

**Dantie Wrapping** adalah aplikasi berbasis web yang digunakan oleh bisnis jasa wrapping/stiker kendaraan untuk:

- Menampilkan **portofolio & layanan** kepada calon pelanggan (landing page)
- **Menerima pemesanan** secara online melalui website
- **Mengelola pesanan** oleh admin (verifikasi, konfirmasi pembayaran, tracking pengerjaan)
- **Mencatat pesanan offline** untuk pelanggan yang datang langsung ke toko
- **Menyediakan dashboard** bagi pelanggan untuk memantau status pesanan mereka
- **Menyediakan REST API** untuk integrasi dengan aplikasi mobile atau frontend SPA



## Stack Teknologi — Penjelasan Detail & Alasan Pemilihan

Setiap teknologi dalam proyek ini dipilih berdasarkan kebutuhan spesifik: aplikasi pemesanan jasa yang harus cepat, mudah dikelola oleh admin non-teknis, dan memiliki dokumentasi yang baik. Berikut penjelasan detailnya:

---

### 🔙 Backend (Server-Side)

#### 1. Laravel 12 (Framework PHP)

| Aspek | Penjelasan |
|-------|------------|
| **Apa itu?** | Laravel adalah framework PHP paling populer di dunia (2015-sekarang). Mengadopsi pola arsitektur **MVC (Model-View-Controller)** yang memisahkan logika data (Model), tampilan (View), dan alur kontrol (Controller). |
| **Mengapa dipilih?** | **Ekosistem lengkap.** Laravel menyediakan hampir semua fitur yang dibutuhkan tanpa harus install package tambahan: routing, database migration, ORM, autentikasi, queue, caching, event system, dan testing. |
| **Peran dalam proyek** | Menjadi **tulang punggung** aplikasi. Semua permintaan dari browser (route web.php) atau dari aplikasi mobile (route api.php) masuk ke Laravel, diproses oleh Controller, menggunakan Model untuk akses database, dan merender tampilan dengan Blade atau mengembalikan JSON. |
| **Fitur khusus yg dipakai** | • **Eloquent ORM** — Query database dengan PHP yang intuitif, relasi antar tabel, lazy/eager loading<br>• **Blade Templating** — Template engine dengan component, slot, dan inheritance<br>• **Migration** — Version control untuk skema database (46 migrasi → di-squash jadi 21)<br>• **Rate Limiter** — Proteksi API dari serangan brute force<br>• **Event & Listener** — Arsitektur event-driven untuk notifikasi otomatis |

#### 2. Filament 5 (Admin Panel Generator)

| Aspek | Penjelasan |
|-------|------------|
| **Apa itu?** | Filament adalah framework untuk membangun admin panel di atas Laravel. Berbeda dari CMS seperti WordPress, Filament memberikan kontrol penuh kepada developer untuk mendesain tampilan dan logika admin. |
| **Mengapa dipilih?** | **Alternatif modern untuk Laravel Nova** (yang berbayar). Filament gratis, open-source, dan memiliki DX (Developer Experience) luar biasa. Dengan Filament, kita bisa membuat CRUD untuk sebuah tabel hanya dalam 5 menit. |
| **Peran dalam proyek** | Menyediakan antarmuka bagi **admin** untuk mengelola: pesanan, layanan, galeri, user, tim perusahaan, dan semua pengaturan konten landing page. Admin tidak perlu menulis kode — cukup klik di panel Filament. |
| **Kenapa bukan Laravel Nova?** | Nova berbayar ($199/site). Filament gratis, fiturnya setara (bahkan lebih unggul di beberapa aspek seperti TALL stack integration), dan komunitasnya sangat aktif. |
| **Komponen Filament yg dipakai** | • **Resources** — CRUD generator untuk setiap model (Pesanan, User, Layanan, dll)<br>• **Pages** — Halaman kustom seperti Laporan Penjualan dengan filter tanggal<br>• **Widgets** — Statistik dashboard (OrderStatsWidget) dan grafik (RevenueChartWidget)<br>• **Forms** — Form builder dengan validasi otomatis<br>• **Tables** — Tabel interaktif dengan sorting, filtering, dan search |

#### 3. Spatie Laravel Permission (Manajemen Role & Akses)

| Aspek | Penjelasan |
|-------|------------|
| **Apa itu?** | Package dari Spatie (vendor Laravel terkenal) untuk mengelola hak akses pengguna. Menggunakan konsep **Role** (peran) dan **Permission** (izin spesifik). |
| **Mengapa dipilih?** | Aplikasi ini membedakan 2 tipe pengguna: **Admin** (bisa akses panel & kelola semua) dan **User** (hanya dashboard customer). Spatie Permission memungkinkan kita mengaturnya dengan middleware `role:admin` di route. |
| **Bagaimana cara kerjanya?** | 1. Di route: `Route::middleware('role:admin')->...` → hanya user dgn role admin bisa akses<br>2. Di Blade: `@if(auth()->user()->hasRole('admin'))` → tampilkan elemen hanya untuk admin<br>3. Database: package ini membuat 3 tabel tambahan (roles, permissions, model_has_roles) untuk menyimpan relasi user-role |
| **Alternatif yang dipertimbangkan** | • **Bouncer** — Lebih sederhana tapi kurang fleksibel<br>• **Gate/Policy bawaan Laravel** — Tidak ada konsep role, hanya permission per-aksi<br>**Keputusan:** Spatie karena paling matang, dokumentasi lengkap, dan mendukung role-based access |

#### 4. Laravel Sanctum (API Authentication)

| Aspek | Penjelasan |
|-------|------------|
| **Apa itu?** | Package autentikasi API ringan dari Laravel. Menggunakan **token-based authentication** — setiap request API menyertakan token di header `Authorization: Bearer <token>`. |
| **Mengapa dipilih?** | Aplikasi ini memiliki REST API yang bisa diakses oleh aplikasi mobile (Android/iOS) atau frontend SPA (React/Vue). Sanctum lebih sederhana daripada **Laravel Passport** (OAuth2) yang terlalu kompleks untuk kebutuhan kita. |
| **Cara kerja** | 1. User login → server buat token → dikirim ke client<br>2. Client simpan token (di localStorage/memory)<br>3. Setiap request API menyertakan token di header<br>4. Server verifikasi token → jika valid, proses request |
| **Mengapa bukan Passport?** | Passport mengimplementasikan OAuth2 penuh (authorization code, client credentials, dll) yang dirancang untuk third-party apps. Sanctum cukup untuk first-party apps (aplikasi kita sendiri). |

#### 5. Laravel Events & Listeners (Arsitektur Event-Driven)

| Aspek | Penjelasan |
|-------|------------|
| **Apa itu?** | Pola arsitektur di mana ketika sesuatu terjadi (event), sistem akan otomatis menjalankan aksi tertentu (listener). Misal: ketika pesanan baru dibuat (event `OrderCreated`), sistem otomatis mengirim notifikasi ke admin (listener `SendOrderCreatedToAdmin`). |
| **Mengapa perlu?** | Tanpa event-driven, kode notifikasi akan tercampur di dalam controller, membuatnya sulit dibaca dan diuji. Dengan event-driven, controller cukup memicu event, dan semua efek samping (notifikasi, email, log) di-handle oleh listener masing-masing. |
| **Ilustrasi alur** | ```
User klik "Beli" → PesananController::store()
                       ↓
                 OrderCreated (event)
                    /        \
                   ↓          ↓
    SendOrderCreatedToAdmin   NotifyPaymentRequired
    (notif ke admin)           (notif ke user untuk bayar)
``` |
| **Event dalam proyek** | `OrderCreated`, `OrderConfirmed`, `OrderCompleted`, `OrderRejected`, `PaymentUploaded`, `PaymentVerified` — mencakup seluruh siklus hidup pesanan. |

---

### 🎨 Frontend (Tampilan & Interaksi)

#### 1. Blade Engine (Templating Laravel)

| Aspek | Penjelasan |
|-------|------------|
| **Apa itu?** | Template engine bawaan Laravel yang memungkinkan kita menulis HTML dengan logika PHP yang bersih. Blade menggunakan ekstensi file `.blade.php`. |
| **Mengapa dipilih?** | Karena aplikasi ini menggunakan **server-side rendering** (SSR) — halaman di-render di server lalu dikirim ke browser sebagai HTML lengkap. Ini lebih cepat untuk first load dibanding SPA (Single Page Application) dan lebih ramah SEO. |
| **Fitur unggulan Blade** | • **Template inheritance** — Layout utama (e.g., `layouts/dashboard_customer.blade.php`) mendefinisikan kerangka, halaman konten hanya `@extends` dan `@section`<br>• **Components** — Potongan UI reusable (navbar, carousel, card)<br>• **PHP expressions** — `{{ $variable }}` otomatis di-escape (XSS protection)<br>• **Directives** — `@auth`, `@guest`, `@if`, `@foreach`, `@push` untuk CSS/JS stack |

#### 2. Tailwind CSS 3 (Utility-First CSS Framework)

| Aspek | Penjelasan |
|-------|------------|
| **Apa itu?** | Framework CSS yang berbeda dari Bootstrap. Alih-alih menyediakan komponen siap pakai (seperti `.btn-primary`), Tailwind menyediakan **utility classes** atomik (seperti `bg-blue-500`, `text-white`, `px-4`, `py-2`, `rounded-lg`) yang kita rangkai sendiri. |
| **Mengapa dipilih?** | • **Kecepatan develop** — Tidak perlu gonta-ganti file CSS. Styling langsung di HTML.<br>• **Ukuran file kecil** — Tailwind membuang CSS yang tidak terpakai (tree-shaking) → file production bisa <10KB<br>• **Konsistensi** — Menggunakan design system (spasi 4px, warna terdefinisi, tipografi terukur)<br>• **Dark mode** — Bawaan Tailwind, tinggal tambah `dark:` prefix |
| **Perbedaan dengan Bootstrap** | Bootstrap: komponen siap pakai, tapi semua website Bootstrap terlihat mirip. Tailwind: kita mendesain sendiri, hasilnya unik, tapi butuh lebih banyak penulisan class. |
| **Mengapa bukan CSS biasa?** | CSS murni untuk proyek sebesar ini akan menghasilkan ribuan baris kode yang sulit di-maintain. Tailwind memberikan batasan (constraint) yang membuat kode CSS tetap teratur. |

#### 3. Alpine.js (Interaktivitas Ringan)

| Aspek | Penjelasan |
|-------|------------|
| **Apa itu?** | Framework JavaScript minimalis untuk menambahkan interaktivitas ke halaman HTML. Sering disebut "Tailwind untuk JavaScript" karena pendekatannya yang deklaratif di HTML. |
| **Mengapa dipilih?** | Aplikasi ini bukan SPA (tidak perlu Vue/React). Namun tetap butuh interaktivitas ringan seperti: toggle dropdown notifikasi, filter galeri, animasi mobile menu. Alpine.js menyediakan itu tanpa perlu build step kompleks. |
| **Perbandingan** | • **Vue/React** → Overkill untuk proyek ini (butuh bundler, virtual DOM, state management)<br>• **jQuery** → Usang, manipulasi DOM langsung rawan bug<br>• **Vanilla JS** → Bisa, tapi kode jadi panjang untuk interaksi sederhana<br>**Alpine.js** → Pas: cukup untuk dropdown, toggle, filter |
| **Contoh penggunaan** | `x-data="{ open: false }"` → buat state, `@click="open = !open"` → toggle, `x-show="open"` → tampilkan/sembunyikan |

#### 4. Phosphor Icons (Ikon UI)

| Aspek | Penjelasan |
|-------|------------|
| **Apa itu?** | Icon set open-source dengan 7.000+ ikon dalam 6 varian gaya (regular, bold, fill, duotone, light, thin). |
| **Mengapa dipilih?** | Ikon sangat penting di dashboard admin & customer untuk memperjelas makna menu. Phosphor memiliki varian **Bold** yang cocok dengan tema gelap (dark mode) aplikasi ini. Alternatif seperti Font Awesome memiliki lisensi yang lebih restriktif. |
| **Cara pakai** | Cukup tambahkan class `ph-bold ph-<nama-ikon>` di elemen `<i>`. Library akan otomatis menggantinya dengan SVG ikon. Tidak perlu import file font besar. |

#### 5. AOS — Animate on Scroll (Animasi Scroll)

| Aspek | Penjelasan |
|-------|------------|
| **Apa itu?** | Library JavaScript untuk menambahkan animasi saat elemen muncul di viewport (terlihat di layar) saat user scroll. |
| **Mengapa dipilih?** | Landing page (beranda, tentang kami, layanan) perlu kesan premium dan profesional. Animasi scroll seperti fade-in, slide-up memberikan pengalaman yang lebih hidup tanpa video berat. |
| **Dampak pada performa** | Ringan — hanya menggunakan CSS transforms dan opacity, tidak memicu layout recalc berat. |

#### 6. SweetAlert2 (Dialog & Notifikasi Popup)

| Aspek | Penjelasan |
|-------|------------|
| **Apa itu?** | Library untuk menggantikan dialog JavaScript bawaan (`alert()`, `confirm()`, `prompt()`) dengan tampilan yang indah dan customizable. |
| **Mengapa dipilih?** | • Konfirmasi sebelum aksi penting (batalkan pesanan, hapus item)<br>• Notifikasi sukses/error setelah operasi<br>• Tampilan yang konsisten dengan tema aplikasi (dark mode)<br>• Mendukung tombol kustom, ikon, timer, dan input form |

#### 7. Chart.js (Grafik & Visualisasi Data)

| Aspek | Penjelasan |
|-------|------------|
| **Apa itu?** | Library JavaScript untuk membuat grafik responsif (bar chart, line chart, pie chart, dll) di halaman web. |
| **Mengapa dipilih?** | Dashboard admin menampilkan grafik pendapatan bulanan dan distribusi status pesanan. Chart.js adalah library grafik paling populer, gratis, dan ringan (60KB minified). Filament juga memiliki integrasi bawaan dengan Chart.js. |
| **Alternatif** | • **ApexCharts** — Fitur lebih banyak tapi ukuran 3x lebih besar<br>• **D3.js** — Paling powerful tapi kurva belajar curam<br>**Keputusan:** Chart.js karena cukup untuk kebutuhan kita dan integrasi Filament mulus |

---

### 🗄️ Database & Infrastruktur

#### 1. MySQL / SQLite (Database Relasional)

| Aspek | Penjelasan |
|-------|------------|
| **MySQL** | Database utama untuk production. Stabil, performa tinggi, mendukung penuh fitur relasional (foreign key, index, transaction). |
| **SQLite** | Database untuk development/testing. Tidak perlu install server database — disimpan dalam satu file `.sqlite`. Cocok untuk development lokal. |
| **Mengapa bukan NoSQL?** | Data pemesanan memiliki relasi kompleks (pesanan → detail → pembayaran → notifikasi). Database relasional (SQL) lebih cocok karena mendukung JOIN, foreign key constraints, dan transaksi ACID. |
| **Optimasi database** | • **Index** pada kolom yang sering di-query (`status`, `id_user`, `tanggal_pesan`)<br>• **Foreign key** untuk menjaga integritas data (tidak bisa hapus user yang punya pesanan)<br>• **Migration** untuk version control skema database |

---

## Fitur Utama

### Landing Page (Publik)
- ✅ Hero section dengan branding perusahaan
- ✅ Galeri portofolio karya wrapping (filter by kategori + jenis)
- ✅ Katalog layanan & paket wrapping
- ✅ Tentang kami (visi, misi, tim, nilai perusahaan)
- ✅ Info kontak & tautan media sosial
- ✅ Responsive design (mobile-first)

### Dashboard Pelanggan
- ✅ Ringkasan statistik (total pesanan, pending, selesai)
- ✅ Riwayat pesanan dengan filter status
- ✅ Halaman checkout (multi-step: layanan → data kendaraan → review → bayar)
- ✅ Upload bukti pembayaran
- ✅ Download invoice PDF
- ✅ Notifikasi real-time (bell icon + dropdown)
- ✅ Keranjang belanja

### Dashboard Admin (via Filament)
- ✅ Manajemen layanan (CRUD paket wrapping)
- ✅ Manajemen galeri (upload & atur portofolio)
- ✅ Manajemen pesanan (ubah status, verifikasi pembayaran, catatan admin)
- ✅ Manajemen pengguna (admin & customer)
- ✅ Manajemen tim perusahaan
- ✅ Pengaturan konten landing page (hero, galeri, layanan, tentang kami)
- ✅ Laporan penjualan (custom Filament page)
- ✅ Widget statistik (total pesanan, pendapatan, pesanan selesai)
- **Pemesanan Offline** — admin dapat membuat pesanan manual untuk pelanggan yang datang langsung

### REST API
- ✅ Autentikasi Sanctum (register, login, logout, profil)
- ✅ Katalog layanan & galeri (publik)
- ✅ Keranjang belanja (CRUD item)
- ✅ Manajemen pesanan (buat, lihat, batalkan, invoice)
- ✅ Upload bukti bayar
- ✅ Notifikasi (daftar, baca, hapus)
- ✅ Admin endpoints (kelola pesanan, verifikasi bayar, statistik dashboard)

---

## Arsitektur Sistem

```
┌─────────────────────────────────────────────────────────────────────┐
│                        BROWSER / MOBILE APP                        │
└─────────────────────────┬───────────────────────────────────────────┘
                          │
          ┌───────────────┴───────────────┐
          │              HTTP              │
          │     Web (Blade) / API (JSON)   │
          └───────────────┬───────────────┘
                          │
┌─────────────────────────┴───────────────────────────────────────────┐
│                        LARAVEL APPLICATION                          │
│                                                                     │
│  ┌─────────────┐  ┌──────────────┐  ┌──────────────────────────┐   │
│  │   Routes    │  │  Middleware  │  │   Controllers            │   │
│  │  web.php    │  │  - throttle  │  │  - Web Controllers       │   │
│  │  api.php    │  │  - auth      │  │  - API Controllers       │   │
│  │  auth.php   │  │  - role      │  │  - Auth Controllers      │   │
│  └──────┬──────┘  │  - verified  │  │  - Admin Controllers     │   │
│         │         └──────┬───────┘  └──────────┬───────────────┘   │
│         ▼                ▼                      ▼                   │
│  ┌──────────────────────────────────────────────────────────────┐   │
│  │                     SERVICES LAYER                            │   │
│  │  KeranjangService, PesananService, PembayaranService,        │   │
│  │  NotifikasiService, CacheService                              │   │
│  └──────────────────────────┬───────────────────────────────────┘   │
│                             ▼                                       │
│  ┌──────────────────────────────────────────────────────────────┐   │
│  │                    ELOQUENT MODELS                            │   │
│  │  User, Pesanan, Keranjang, Layanan, Galeri, Pembayaran,      │   │
│  │  Notifikasi, DetailPesanan, DetailKeranjang, ProfilPerusahaan│   │
│  └──────────────────────────┬───────────────────────────────────┘   │
│                             ▼                                       │
│  ┌──────────────────────────────────────────────────────────────┐   │
│  │                     DATABASE (MySQL)                          │   │
│  └──────────────────────────────────────────────────────────────┘   │
│                                                                     │
│  ┌──────────────────────────────────────────────────────────────┐   │
│  │              FILAMENT ADMIN PANEL                             │   │
│  │  Resources (CRUD) │ Pages (Custom) │ Widgets (Stats/Chart)   │   │
│  └──────────────────────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────────────────┘
```

### Pola Desain

- **MVC (Model-View-Controller)** — Struktur standar Laravel
- **Service Layer** — Logika bisnis dipisahkan ke service class (`app/Services/`)
- **Event-Driven Architecture** — Notifikasi menggunakan Event & Listener
- **Repository Pattern** — Settings menggunakan Spatie Settings + Cache layer

---

## Struktur Folder

```
informasi_pemesanan_wrapping/
│
├── app/
│   ├── Console/Commands/           # Artisan commands kustom
│   │   ├── InitializeHomepageSettings.php
│   │   └── OptimizePerformance.php
│   │
│   ├── Enums/                      # Enum (tipe data aman)
│   │   ├── OrderStatus.php         #   Status pesanan
│   │   ├── PaymentMethod.php       #   Metode pembayaran
│   │   ├── PaymentStatus.php       #   Status pembayaran
│   │   └── NotificationType.php    #   Tipe notifikasi
│   │
│   ├── Events/                     # Event yang dipicu
│   │   ├── OrderCreated.php        #   Pesanan baru dibuat
│   │   ├── OrderConfirmed.php      #   Pesanan dikonfirmasi
│   │   ├── OrderCompleted.php      #   Pengerjaan selesai
│   │   ├── OrderRejected.php       #   Pesanan ditolak
│   │   ├── PaymentUploaded.php     #   Bukti bayar diupload
│   │   └── PaymentVerified.php     #   Pembayaran diverifikasi
│   │
│   ├── Filament/                   # Panel Admin Filament
│   │   ├── Concerns/               #   Trait untuk setting page
│   │   ├── Pages/                  #   Halaman kustom (bukan resource)
│   │   │   └── LaporanPenjualan.php
│   │   ├── Resources/              #   CRUD Resource per model
│   │   │   ├── Pesanans/Pesanans/  #     Manajemen pesanan online
│   │   │   ├── Pesanans/PesananOffline/ # Pemesanan offline
│   │   │   ├── Layanans/           #     Manajemen layanan
│   │   │   ├── Galeris/            #     Manajemen galeri
│   │   │   ├── Users/              #     Manajemen user
│   │   │   ├── TeamMembers/        #     Tim perusahaan
│   │   │   ├── RiwayatPesanans/    #     Read-only riwayat
│   │   │   └── *.php               #     Setting resource (single-record)
│   │   └── Widgets/                #   Widget dashboard
│   │       ├── OrderStatsWidget.php
│   │       ├── RecentOrdersWidget.php
│   │       └── RevenueChartWidget.php
│   │
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/              #   Controller untuk admin blade
│   │   │   │   └── OfflineOrderController.php
│   │   │   ├── Api/                #   REST API Controllers
│   │   │   │   ├── Admin/          #     Endpoints khusus admin
│   │   │   │   └── *.php           #     Endpoints user
│   │   │   ├── Auth/               #   Auth Breeze
│   │   │   ├── *.php               #   Web controllers
│   │   │   └── Controller.php      #   Base controller
│   │   ├── Middleware/             # Middleware kustom
│   │   │   ├── RoleMiddleware.php
│   │   │   └── ShareSettingsToViews.php
│   │   └── Requests/               # Form request validation
│   │       ├── Admin/
│   │       ├── Auth/
│   │       ├── Keranjang/
│   │       ├── Pembayaran/
│   │       └── Pesanan/
│   │
│   ├── Listeners/                  # Listener untuk event
│   │   ├── SendOrderCreatedToAdmin.php
│   │   ├── NotifyPaymentRequired.php
│   │   ├── SendPaymentUploadedToAdmin.php
│   │   ├── SendOrderConfirmationEmail.php
│   │   ├── NotifyOrderCompleted.php
│   │   ├── NotifyOrderProcessingStarted.php
│   │   └── NotifyOrderRejection.php
│   │
│   ├── Models/                     # Eloquent Models
│   │   ├── User.php
│   │   ├── Pesanan.php             #   Inti sistem pemesanan
│   │   ├── Keranjang.php
│   │   ├── Layanan.php
│   │   ├── Galeri.php
│   │   ├── Pembayaran.php
│   │   ├── Notifikasi.php
│   │   ├── DetailPesanan.php
│   │   ├── DetailKeranjang.php
│   │   ├── FormPesanan.php
│   │   ├── ProfilPerusahaan.php
│   │   ├── TeamMember.php
│   │   ├── Testimoni.php
│   │   └── DummyModel.php          #   Untuk setting Filament single-record
│   │
│   ├── Providers/                  # Service Providers
│   │   └── Filament/
│   │       └── AdminPanelProvider.php
│   │
│   ├── Services/                   # Service Layer (logika bisnis)
│   │   ├── KeranjangService.php
│   │   ├── PesananService.php
│   │   ├── PembayaranService.php
│   │   ├── NotifikasiService.php
│   │   └── CacheService.php
│   │
│   ├── Settings/                   # Spatie Settings
│   │   ├── HomepageSettings.php
│   │   ├── LayananSettings.php
│   │   ├── GaleriSettings.php
│   │   └── ...
│   │
│   └── Traits/
│       └── ApiResponse.php         # Trait untuk response JSON API
│
├── resources/views/                # Blade Views
│   ├── layouts/                    # Layout utama
│   │   ├── tampilan_utama.blade.php    # Layout landing page
│   │   └── dashboard_customer.blade.php # Layout dashboard customer
│   ├── landing/                    # Halaman publik
│   │   ├── beranda/
│   │   ├── layanan/
│   │   ├── galeri/
│   │   ├── tentang-kami/
│   │   └── profil/
│   ├── dashboard/customer/         # Dashboard pelanggan
│   │   ├── dashboard/              #   Halaman dashboard
│   │   ├── pesanan/                #   Manajemen pesanan
│   │   └── keranjang/              #   Keranjang belanja
│   ├── admin/offline-orders/       #   Form pemesanan offline
│   └── filament/                   #   Kustomisasi Filament
│
├── routes/                         # Route definitions
│   ├── web.php                     #   Web routes (browser)
│   ├── api.php                     #   API routes (JSON)
│   └── auth.php                    #   Auth routes (Breeze)
│
├── database/migrations/            # Migrasi database (46 file)
│
└── config/                         # Konfigurasi aplikasi
    ├── app-settings.php            #   Settings khusus aplikasi
    ├── permission.php              #   Spatie Permission
    └── settings.php                #   Spatie Settings
```

---

## Database & Migrasi

### Tabel Utama

| Tabel | Fungsi |
|-------|--------|
| `users` | Data pengguna (customer & admin) |
| `pesanans` | Data pesanan/order — inti transaksi |
| `detail_pesanans` | Item layanan dalam satu pesanan (bisa >1) |
| `form_pesanans` | Data kendaraan (model, warna, plat, jadwal) |
| `keranjangs` | Keranjang belanja per user |
| `detail_keranjangs` | Item dalam keranjang |
| `pembayarans` | Bukti & status pembayaran |
| `layanans` | Daftar layanan/paket wrapping |
| `galeris` | Portofolio karya wrapping |
| `notifikasis` | Notifikasi push untuk user |
| `profil_perusahaans` | Settings landing page (single-row) |
| `team_members` | Tim perusahaan (ditampilkan di halaman tentang kami) |
| `testimonis` | Testimoni pelanggan |
| `model_has_roles` | Relasi user → role (Spatie) |

### Migrasi Penting

| File | Perubahan |
|------|-----------|
| `2022_12_14_083707_create_settings_table.php` | Tabel settings Spatie |
| `2026_04_20_161226_create_permission_tables.php` | Tabel roles & permissions Spatie |
| `2026_05_12_100000_create_keranjangs_table.php` | Keranjang + detail |
| `2026_05_12_110000_create_pesanans_table.php` | Pesanan + detail + form + pembayaran + notifikasi |
| `2026_06_07_210000_add_order_source_to_pesanans_table.php` | Kolom untuk offline orders |
| `2026_06_07_220000_make_id_user_nullable_in_pesanans_table.php` | id_user nullable utk offline |

---

## Alur Status Pesanan

```
                    ┌──────────────────────────┐
                    │  Menunggu Konfirmasi Admin│  ← Pesanan baru masuk
                    └────────────┬─────────────┘
                         │                │
                         ▼                ▼
              ┌──────────────┐    ┌──────────────┐
              │  Ditolak     │    │Tunggu Bayar  │  ← Admin konfirmasi
              └──────────────┘    └──────┬───────┘
                                         │
                                    (upload bukti)
                                         ▼
                          ┌─────────────────────────┐
                          │Menunggu Verifikasi Bayar │  ← Admin cek bukti
                          └────────────┬────────────┘
                               │                │
                               ▼                ▼
                    ┌──────────────┐    ┌──────────────┐
                    │  Ditolak     │    │Pembayaran OK  │  ← Admin verifikasi
                    └──────────────┘    └──────┬───────┘
                                               ▼
                                    ┌──────────────────┐
                                    │  Sedang Diproses  │  ← Admin mulai kerja
                                    └──────┬───────────┘
                                           ▼
                                    ┌──────────────────┐
                                    │     Selesai       │
                                    └──────────────────┘
```

Status yang diakui sebagai **pendapatan (revenue)**: hanya `selesai`

---

## Hak Akses (Role & Permission)

Sistem menggunakan **Spatie Laravel Permission** dengan 2 role:

| Role | Akses |
|------|-------|
| `admin` | Filament panel, API admin, offline orders, laporan, semua manajemen |
| `user` | Dashboard customer, keranjang, pesanan sendiri, upload bukti bayar |

Middleware yang digunakan:
- `role:admin` — Hanya admin
- `role:admin|user` — Admin & user
- `auth` — Wajib login (Laravel)
- `verified` — Wajib verifikasi email

---

## API Endpoints

### Publik (tanpa token)

| Method | Endpoint | Fungsi |
|--------|----------|--------|
| POST | `/api/auth/register` | Daftar user baru |
| POST | `/api/auth/login` | Login (dapat token) |
| GET | `/api/layanan` | Daftar layanan |
| GET | `/api/layanan/{id}` | Detail layanan |
| GET | `/api/layanan/kategori/{kategori}` | Filter by kategori |
| GET | `/api/galeri` | Semua galeri |
| GET | `/api/galeri/kategori` | Daftar kategori |
| GET | `/api/galeri/{kategori}/jenis` | Filter by kategori |

### Terproteksi (Bearer Token)

| Method | Endpoint | Fungsi |
|--------|----------|--------|
| POST | `/api/auth/logout` | Logout |
| GET | `/api/auth/me` | Profil user |
| GET | `/api/keranjang` | Lihat keranjang |
| POST | `/api/keranjang/item` | Tambah item |
| PUT | `/api/keranjang/item/{id}` | Update item |
| DELETE | `/api/keranjang/item/{id}` | Hapus item |
| DELETE | `/api/keranjang/clear` | Kosongkan |
| GET | `/api/pesanan` | Riwayat pesanan |
| POST | `/api/pesanan` | Buat pesanan |
| GET | `/api/pesanan/{id}` | Detail |
| GET | `/api/pesanan/{id}/invoice` | Data invoice |
| POST | `/api/pesanan/{id}/pembayaran/upload` | Upload bukti |
| GET | `/api/pesanan/{id}/pembayaran/status` | Status bayar |
| GET | `/api/notifikasi` | Notifikasi |
| PUT | `/api/pesanan/{id}` | Batalkan pesanan |

### Admin (Bearer Token + role:admin)

| Method | Endpoint | Fungsi |
|--------|----------|--------|
| GET | `/api/admin/pesanan` | Semua pesanan |
| GET | `/api/admin/pesanan/{id}` | Detail pesanan |
| PUT | `/api/admin/pesanan/{id}/status` | Ubah status |
| POST | `/api/admin/pesanan/{id}/note` | Tambah catatan |
| GET | `/api/admin/pembayaran` | Daftar bayar |
| PUT | `/api/admin/pembayaran/{id}/verify` | Verifikasi |
| PUT | `/api/admin/pembayaran/{id}/reject` | Tolak |
| GET | `/api/admin/dashboard/stats` | Statistik |
| GET | `/api/admin/dashboard/chart-data` | Data grafik |

---

## Filament Admin Panel

Panel admin Filament menyediakan antarmuka untuk mengelola seluruh aspek aplikasi.

### Resources (CRUD)

| Resource | Grup Navigasi | Fungsi |
|----------|---------------|--------|
| **Pemesanan Offline** | Laporan & Pembayaran | Buat & kelola pesanan offline |
| **Pesanan** | Kelola User Dashboard | Kelola semua pesanan online (ubah status, verifikasi bayar) |
| **Riwayat Pesanan** | Kelola User Dashboard | Lihat-read all riwayat (read-only) |
| **Laporan Penjualan** | Laporan & Pembayaran | Cetak laporan per hari/bulan/tahun |
| **Layanan** | Manajemen Konten | CRUD paket wrapping |
| **Galeri** | Manajemen Konten | Upload & atur portofolio |
| **Team Members** | Manajemen Tim & Perusahaan | CRUD anggota tim |
| **Users** | Pengaturan Sistem | Manajemen user & role |
| **Settings (9 resource)** | Pengaturan Sistem | Single-record settings (hero, konten, dashboard, dll) |

### Widget Dashboard

| Widget | Fungsi |
|--------|--------|
| `OrderStatsWidget` | Total pesanan, pendapatan, perlu verifikasi, selesai |
| `RecentOrdersWidget` | 10 pesanan terbaru |
| `RevenueChartWidget` | Grafik pendapatan bulanan |

---

## Komponen Kunci

### 1. Service Layer (`app/Services/`)

Logika bisnis yang kompleks dipisahkan ke service class agar controller tetap *thin*:

| Service | Tanggung Jawab |
|---------|----------------|
| `KeranjangService` | Tambah/hapus/update item, hitung subtotal, clear cart |
| `PesananService` | Buat pesanan dari keranjang, generate kode, kalkulasi total |
| `PembayaranService` | Upload bukti, verifikasi, update status |
| `NotifikasiService` | Kirim notifikasi ke user, mark as read |
| `CacheService` | Cache dashboard stats & chart data |

### 2. Event-Driven Notifications (`app/Events/` + `app/Listeners/`)

Event dipicu saat terjadi perubahan status, listener akan mengirim notifikasi:

| Event | Listener |
|-------|----------|
| `OrderCreated` | `SendOrderCreatedToAdmin` → notif ke admin |
| `OrderCreated` | `NotifyPaymentRequired` → notif ke user untuk bayar |
| `PaymentUploaded` | `SendPaymentUploadedToAdmin` → notif admin cek bukti |
| `OrderConfirmed` | `SendOrderConfirmationEmail` → notif user |
| `OrderCompleted` | `NotifyOrderCompleted` → selesai |
| `OrderRejected` | `NotifyOrderRejection` → ditolak (dengan alasan) |

### 3. DummyModel — Solusi untuk Filament Single-Record Settings

`DummyModel` adalah model palsu yang digunakan oleh resource Filament untuk mengelola setting single-record (seperti `HomepageSettings`, `CompanySettings`, dll). Settings ini disimpan di tabel `settings` (Spatie) bukan di tabel database biasa. DummyModel memungkinkan Filament CRUD bekerja dengan format yang tidak memiliki tabel sendiri.

---

## Cara Instalasi

### Prasyarat

- PHP 8.2+
- Composer 2.x
- MySQL 8+ (atau SQLite untuk development)
- Node.js 18+ (untuk frontend build)

### Langkah Instalasi

```bash
# 1. Clone repositori
git clone <repo-url>
cd informasi_pemesanan_wrapping

# 2. Install dependencies PHP
composer install

# 3. Copy environment
cp .env.example .env
# Atur konfigurasi database di .env

# 4. Generate key
php artisan key:generate

# 5. Jalankan migrasi & seeder
php artisan migrate --seed

# 6. Install dependencies frontend
npm install && npm run build

# 7. Setup Filament (publish assets)
php artisan filament:assets

# 8. Buat user admin
php artisan filament:make-user
# Masukkan email, password, dan assign role 'admin' via database

# 9. Jalankan development server
php artisan serve

# 10. (Opsional) Inisialisasi settings landing page
php artisan app:initialize-settings
```

### Login Default

| Role | Email (buat sendiri melalui filament:make-user) |
|------|------------------------------------------------|
| Admin | admin@example.com |
| User | Registrasi via halaman register |

---

## Pengembangan

### Perintah Berguna

```bash
# Buat resource Filament baru
php artisan make:filament-resource NamaResource

# Buat model dengan migrasi
php artisan make:model NamaModel -m

# Clear cache
php artisan view:clear
php artisan route:clear
php artisan config:clear

# Optimasi production
php artisan optimize

# Buat user admin Filament
php artisan make:filament-user
```

### Menambahkan Fitur Baru

1. **Model** → Buat model + migrasi (`php artisan make:model -m`)
2. **Controller (Web)** → Buat controller + route di `web.php`
3. **Controller (API)** → Buat controller API + route di `api.php`
4. **Filament Resource** → Buat resource di `app/Filament/Resources/`
5. **Blade View** → Buat view di `resources/views/`
6. **Event** → Jika perlu notifikasi otomatis, buat Event + Listener

### Catatan Penting

- Jangan ubah konstanta status di `Pesanan` model tanpa update di `OrderStatus` enum
- Notifikasi dikirim via Event/Listener, **bukan** dari model `booted()`
- Pastikan selalu menggunakan `Pesanan::STATUS_*` constants, bukan string langsung

---

## Lisensi

Hak Cipta © 2026 Dantie Wrapping Service. Seluruh hak cipta dilindungi.
