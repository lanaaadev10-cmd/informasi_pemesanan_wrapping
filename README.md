# 🎁 Informasi Pemesanan Wrapping

Sistem informasi pemesanan jasa wrapping berbasis Laravel + Filament v5.

> ⚠️ **Aturan Tim**: Semua pengerjaan dilakukan di branch masing-masing.
> Jangan langsung push ke `main`. Kerjakan di branch → PR → merge ke main.

---

## 📌 Alur Kerja Tim

```
1. git fetch --all
2. git checkout <nama-branch-kamu>
3. Kerjakan fitur di branch kamu
4. git add . && git commit -m "pesan commit"
5. git push origin <nama-branch-kamu>
6. Buat Pull Request ke main di GitHub
```

---

## 🌿 Daftar Branch & Penanggungjawab

### 🔵 Backend — Logika & Database

| Branch | Tugas | Isi Saat Ini |
|---|---|---|
| `backend/cms-dynamic` | CMS dinamis (landing page, konten) | ✅ Ada code (266 file) |
| `backend/katalog-layanan` | Manajemen katalog layanan | ✅ Ada code (187 file) |
| `backend/keranjang` | Keranjang & detail keranjang | ✅ Ada code (205 file) |
| `backend/pemesanan` | Pesanan, pembayaran, notifikasi | ✅ Ada code (211 file) |
| `backend/profil_perusahaan` | Profil perusahaan & seeder | ✅ Ada code (199 file) |

---

### 🎨 Frontend — Tampilan & UI

| Branch | Tugas | Isi Saat Ini |
|---|---|---|
| `frontend/galeri` | Halaman galeri pekerjaan | ✅ Ada code (199 file) |
| `frontend/katalog` | Halaman katalog layanan | ✅ Ada code (199 file) |
| `frontend/keranjang` | Halaman keranjang belanja | ✅ Ada code (221 file) |
| `frontend/profil_perusahaan` | Halaman profil & beranda | ✅ Ada code (200 file) |
| `frontend/ui-modernization` | Modernisasi tampilan dashboard customer | ✅ Ada code (233 file) |

---

### 🔌 API — Endpoint & Integrasi

| Branch | Tugas | Isi Saat Ini |
|---|---|---|
| `api/keranjang` | API endpoint keranjang | ✅ Ada code (271 file) |
| `api/layanan` | API endpoint layanan | ✅ Ada code (271 file) |
| `api/notifikasi` | API endpoint notifikasi | ✅ Ada code (271 file) |
| `api/pembayaran` | API endpoint pembayaran | ✅ Ada code (271 file) |
| `api/pesanan` | API endpoint pesanan | ✅ Ada code (271 file) |
| `api/user-profile` | API endpoint profil pengguna | ✅ Ada code (271 file) |

---

### ✨ Feature — Fitur Tambahan

| Branch | Tugas | Isi Saat Ini |
|---|---|---|
| `feature/customer-dashboard-checkout` | Dashboard & checkout customer | ✅ Ada code (271 file) |
| `feature/documentation` | Dokumentasi sistem | ✅ Ada code (271 file) |
| `feature/laporan-penjualan` | Laporan & cetak PDF penjualan | ✅ Ada code (271 file) |
| `feature/modernize-katalog` | Modernisasi halaman katalog | ✅ Ada code (271 file) |

---

### 🔧 Fix & Lainnya

| Branch | Tugas | Isi Saat Ini |
|---|---|---|
| `fix-profil-katalog` | Perbaikan bug profil & katalog | ✅ Ada code (271 file) |
| `galeri_pekerjaan` | Fitur galeri pekerjaan | ✅ Ada code (271 file) |
| `a-` | Branch percobaan/misc | ✅ Ada code (228 file) |

---

## ⚙️ Tech Stack

-- **Framework**: Laravel 12 + Breeze
- **Admin Panel**: Filament v5
- **Database**: MySQL
- **Frontend**: Blade + Tailwind CSS

---

## 🚀 Setup Lokal

```bash
git clone https://github.com/lanaaadev10-cmd/informasi_pemesanan_wrapping.git
cd informasi_pemesanan_wrapping
git checkout <nama-branch-kamu>
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```
