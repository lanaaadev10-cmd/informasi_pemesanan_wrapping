# AGENTS.md — Informasi Pemesanan Wrapping

## Ringkasan Proyek

**Tujuan:** Aplikasi pemesanan jasa _car wrapping_ (laminasi kendaraan) berbasis web. Pelanggan bisa memilih paket, memesan, upload bukti bayar, dan lacak status. Admin kelola layanan, galeri, pesanan, dan verifikasi bayar via panel Filament.

**Masalah yang diselesaikan:**
- Pemesanan manual (telepon/WA) rawan salah catat → sistem terpusat
- Pelanggan sulit lacak status → notifikasi & dashboard real-time
- Admin kewalahan kelola banyak pesanan → workflow status & panel admin
- Tidak ada arsip digital → riwayat pesanan & bukti bayar tersimpan

**Pengguna:** Customer (user), Admin, Tamu (guest).

## Stack
- Laravel 12 + Filament 5 + Spatie Laravel Settings + Spatie Laravel Permission + Sanctum
- Tailwind CSS v3 + Alpine.js + Vite

## Key Commands
```bash
composer dev            # Runs php artisan serve + queue:listen + pail + vite (concurrently)
npm run dev             # Vite HMR only
npm run build           # Production asset build
php artisan migrate
php artisan db:seed
php artisan optimize:clear     # Clear cache (cache breaks Spatie settings)
php artisan settings:cache     # Cache discovered settings classes
php artisan test               # Pest tests
```

## Database Conventions
- **Table names**: Indonesian plural (`pesanans`, `layanans`, `galeris`, `keranjangs`, `detail_pesanans`, `notifikasis`)
- **Primary keys**: Custom names (`id_pesanan`, `id_layanan`, `id_galeri`, `id_detail`, `id_keranjang`)
- **User FK**: `id_user` referencing `users.id`
- **No `service_prices`, `vehicle_types`, or `order_status_logs` tables** — `harga` is directly on `layanans`
- **No `dompdf` installed** — PDF generation not implemented yet

## Static Content — Public Pages
Semua halaman publik (beranda, galeri, layanan, tentang-kami, profil) sepenuhnya **static/hardcoded** di view. Admin panel tidak bisa mengedit konten publik.

- `app/Helpers/StaticContent.php` — data statis terpusat (navbar, hero, teks, dll.)
- Gambar di `public/images/{logo,background,galeri,umum}/`
- Controller langsung `return view(...)` tanpa query DB:
  - `DashboardController` — beranda, layanan, tentang-kami, profil
  - `GaleriController` — galeri
- `SettingsServiceProvider` tetap jalan untuk internal dashboard/admin, skip data publik

## Admin Panel (Filament 5)
- Panel config in `app/Providers/Filament/AdminPanelProvider.php` (no `config/filament.php`)
- Auto-discovers resources from `app/Filament/Resources/`, pages from `app/Filament/Pages/`
- Navigation groups: **Website**, **Konten**, **Transaksi**, **Pengaturan** (collapsed by default)
- Brand name: `Admin Wrapping`
- Primary color: Orange

## Filament Resources (remaining after cleanup)
| Resource | Backing | Group |
|---|---|---|
| `Galeris/GaleriResource` | Eloquent `Galeri` | Konten |
| `Layanans/LayananResource` | Eloquent `Layanan` | Konten |
| `Pesanans/PesananResource` | Eloquent `Pesanan` | Transaksi |
| `RiwayatPesanans/RiwayatPesananResource` | Eloquent `Pesanan` (filtered) | Transaksi |
| `CompanyResource` | DummyModel → `CompanySettings` | Pengaturan |
| `Users/UserResource` | Eloquent `User` | — |

## Deleted Resources (no longer in admin)
| Resource | Group | Reason |
|---|---|---|
| `BerandaResource` (+ folder) | Website | Diganti static |
| `TentangKamiResource` (+ folder) | Website | Diganti static |
| `TentangKamiSettingResource` (+ folder) | Website | Duplikat, diganti static |
| `ProfilPageResource` (+ folder) | Website | Diganti static |
| `ContentResource` (+ folder) | Website | Diganti static |
| `DashboardCustomerResource` (+ folder) | Website | Diganti static |
| `KeranjangCheckoutResource` (+ folder) | Website | Diganti static |
| `PesananSettingResource` (+ folder) | Website | Diganti static |
| `HalamanGaleriResource` (+ folder) | Website | Diganti static |
| `Testimonis/` | Konten | Diganti static |
| `LandingFiturs/` | Konten | Diganti static |
| `TeamMembers/` | Konten | Diganti static |
| `LayoutResource` (+ folder) | Pengaturan | Diganti static |
| `HalamanLayananResource` (+ folder) | Website | Diganti static |
| `KatalogResource` (+ folder) | Website | Diganti static |
| `GaleriSettings` | Settings class | Tidak dipakai |

## Galeri Model
- `app/Models/Galeri.php` (PK: `id_galeri`, table: `galeris`)
- Fillable: judul, foto, deskripsi, tanggal_upload, sub_judul, is_featured, badge_text
- Kolom `kategori` pernah ada lalu di-drop via migration `2026_07_02_000002`
- Migration: `2026_07_02_000001_create_galeris_table.php`

## Pesanan (Order) Model
- Table: `pesanans`, PK: `id_pesanan`
- Status constants (string): `menunggu_konfirmasi_admin`, `menunggu_pembayaran`, `menunggu_verifikasi_pembayaran`, `dikonfirmasi`, `sedang_diproses`, `selesai`, `ditolak`
- Accessors: `getLabelStatusAttribute()` (human-readable), `getWarnaBadgeAttribute()` (Tailwind color name)
- Helpers: `bisaUploadBukti()`, `bisaUnduhInvoice()`
- Relasi: `belongsTo User` (id_user), `hasMany DetailPesanan` (detail_pesanans), `hasOne FormPesanan` (form_pesanans), `hasOne Pembayaran` (pembayarans), `hasMany Notifikasi` (notifikasis)

## Views
- **Public layout**: `layouts.tampilan_utama` — dark theme (`#0a0a0a`), Plus Jakarta Sans font, AOS animations, Phosphor icons, preloader. Navbar & footer hardcoded.
- **Customer layout**: `layouts.dashboard_customer` — dark sidebar + bottom nav (mobile), notification bell, cart badge
- **Landing pages** in `resources/views/landing/` (beranda, galeri, katalog, layanan, profil, tentang-kami)
- **Customer dashboard** in `resources/views/dashboard/customer/` (dashboard, keranjang, pesanan, katalog)

## Important Quirks
- GET `/logout` exists to work around 419 Page Expired on POST logout (Laravel Breeze quirk)
- Email verification is enforced for protected routes (`verified` middleware)
- Throttle: 60 requests per 5 minutes on public routes
- Settings classes masih ada di `app/Settings/` walau resourcenya sudah dihapus — tidak aktif di admin
- `composer dump-autoload` mungkin timeout di CLI — jika error Class not found, jalankan manual
