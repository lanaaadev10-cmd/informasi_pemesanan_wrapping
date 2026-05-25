# 🎁 Informasi Pemesanan Wrapping

Sistem informasi pemesanan jasa wrapping berbasis web. Dibangun menggunakan **Laravel 12** dengan **Filament v5** sebagai admin panel, **Blade + Tailwind CSS** untuk frontend, serta **Spatie Permission** dan **Laravel Sanctum** untuk manajemen akses dan autentikasi API.

---

## 📋 Daftar Isi

- [Tech Stack](#tech-stack)
- [Fitur](#fitur)
- [Persyaratan Sistem](#persyaratan-sistem)
- [Instalasi](#instalasi)
- [Menjalankan Aplikasi](#menjalankan-aplikasi)
- [Alur Kerja Tim](#alur-kerja-tim)
- [Daftar Branch](#daftar-branch--penanggungjawab)
- [Tim Pengembang](#tim-pengembang)

---

## Tech Stack

| Kategori | Teknologi |
|---|---|
| **Framework** | Laravel 12 |
| **Admin Panel** | Filament v5 |
| **Frontend** | Blade Engine + Tailwind CSS v3 + Alpine.js |
| **API Auth** | Laravel Sanctum |
| **Role & Permission** | Spatie Laravel Permission v7 |
| **Database** | MySQL |
| **Asset Bundler** | Vite + Laravel Vite Plugin |
| **HTTP Client** | Axios |
| **Queue** | Database driver |
| **Testing** | Pest PHP |
| **Code Style** | Laravel Pint |

---

## Fitur

- 📦 Manajemen katalog layanan wrapping
- 🛒 Keranjang belanja customer
- 📋 Pemesanan dan tracking pesanan
- 💳 Pembayaran online
- 🔔 Notifikasi status pesanan
- 🖼️ Galeri pekerjaan wrapping
- 🏢 Profil perusahaan (CMS dinamis)
- 👥 Manajemen role & permission (admin, customer)
- 📊 Dashboard admin (Filament)
- 🧾 Laporan penjualan (PDF)
- 🔐 Autentikasi multi-level (Sanctum)

---

## Persyaratan Sistem

- PHP ^8.2
- Composer
- Node.js ^18 / ^20
- MySQL / MariaDB
- Laragon / XAMPP / Docker

---

## Instalasi

### 1. Clone Repository

```bash
git clone https://github.com/lanaaadev10-cmd/informasi_pemesanan_wrapping.git
cd informasi_pemesanan_wrapping
```

### 2. Pindah ke Branch Tugas

```bash
git fetch --all
git checkout <nama-branch-kamu>
```

> ⚠️ Jangan langsung push ke `main`. Seluruh pengerjaan dilakukan di branch masing-masing.

### 3. Install Dependency PHP

```bash
composer install
```

### 4. Konfigurasi Environment

```bash
cp .env.example .env
```

Edit file `.env` dan sesuaikan konfigurasi database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=informasi_pemesanan_wrapping
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Generate App Key

```bash
php artisan key:generate
```

### 6. Install Dependency Frontend

```bash
npm install
```

### 7. Migrasi & Seed Database

```bash
php artisan migrate --seed
```

### 8. Link Storage

```bash
php artisan storage:link
```

---

## Menjalankan Aplikasi

### Development Server

```bash
php artisan serve
```

Akses di: `http://localhost:8000`

### Vite (Hot Reload)

Jalankan di terminal terpisah:

```bash
npm run dev
```

### Mode Development Lengkap (Server + Queue + Logs + Vite)

```bash
composer dev
```

Menjalankan secara bersamaan:
- `php artisan serve` → Web server
- `php artisan queue:listen --tries=1` → Queue worker
- `php artisan pail --timeout=0` → Log viewer
- `npm run dev` → Vite HMR

### Filament Admin Panel

Akses admin panel di:

```
http://localhost:8000/admin
```

> Jalankan `php artisan filament:upgrade` jika ada perubahan pada package Filament.

---

## Alur Kerja Tim

```
1. git fetch --all
2. git checkout <nama-branch-kamu>
3. Kerjakan fitur di branch kamu
4. git add .
5. git commit -m "feat: deskripsi perubahan"
6. git push origin <nama-branch-kamu>
7. Buat Pull Request (PR) ke main di GitHub
```

### Aturan Commit

Gunakan prefix konvensional:

| Prefix | Kegunaan |
|---|---|
| `feat:` | Fitur baru |
| `fix:` | Perbaikan bug |
| `refactor:` | Refaktor kode |
| `style:` | Perbaikan UI/tampilan |
| `docs:` | Dokumentasi |
| `chore:` | Tugas teknis (dependencies, config) |

---

## Daftar Branch & Penanggungjawab

### Backend — Logika & Database

| Branch | Tugas |
|---|---|
| `backend/cms-dynamic` | CMS dinamis (landing page, konten) |
| `backend/katalog-layanan` | Manajemen katalog layanan |
| `backend/keranjang` | Keranjang & detail keranjang |
| `backend/pemesanan` | Pesanan, pembayaran, notifikasi |
| `backend/profil_perusahaan` | Profil perusahaan & seeder |

### Frontend — Tampilan & UI

| Branch | Tugas |
|---|---|
| `frontend/galeri` | Halaman galeri pekerjaan |
| `frontend/katalog` | Halaman katalog layanan |
| `frontend/keranjang` | Halaman keranjang belanja |
| `frontend/profil_perusahaan` | Halaman profil & beranda |
| `frontend/ui-modernization` | Modernisasi dashboard customer |

### API — Endpoint & Integrasi

| Branch | Tugas |
|---|---|
| `api/keranjang` | API endpoint keranjang |
| `api/layanan` | API endpoint layanan |
| `api/notifikasi` | API endpoint notifikasi |
| `api/pembayaran` | API endpoint pembayaran |
| `api/pesanan` | API endpoint pesanan |
| `api/user-profile` | API endpoint profil pengguna |

### Feature — Fitur Tambahan

| Branch | Tugas |
|---|---|
| `feature/customer-dashboard-checkout` | Dashboard & checkout customer |
| `feature/documentation` | Dokumentasi sistem |
| `feature/laporan-penjualan` | Laporan & cetak PDF penjualan |
| `feature/modernize-katalog` | Modernisasi halaman katalog |

### Fix & Lainnya

| Branch | Tugas |
|---|---|
| `fix-profil-katalog` | Perbaikan bug profil & katalog |
| `galeri_pekerjaan` | Fitur galeri pekerjaan |
| `a-` | Branch percobaan/misc |

---

## Tim Pengembang

**[lanaaadev10-cmd](https://github.com/lanaaadev10-cmd)** 
**[Hillmi-Nazwar](https://github.com/Hillmi-Nazwar)** 
**[ahmadsepta2405](https://github.com/ahmadsepta2405)** 
**[zenverovenopasa](https://github.com/zenverovenopasa)** |


## Lisensi

Proyek ini dikembangkan untuk keperluan **Project Based Learning (PBL)**.
