# Sistem Informasi & Pemesanan Jasa Wrapping Kendaraan (Dantie Stiker)
> **Laporan Audit Sistem, Cetak Biru Arsitektur Alur Bisnis & Matriks Jaminan Mutu (QA & Product Manager Blueprint)**

[![Laravel Version](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=flat-square&logo=laravel)](https://laravel.com)
[![Filament Version](https://img.shields.io/badge/Filament-5.x-F59E0B?style=flat-square&logo=filament)](https://filamentphp.com)
[![PHP Version](https://img.shields.io/badge/PHP-%5E8.2-777BB4?style=flat-square&logo=php)](https://php.net)
[![Test Suite](https://img.shields.io/badge/Tests-83%20Passed%20(100%25)-success?style=flat-square&logo=pest)](https://pestphp.com)
[![License](https://img.shields.io/badge/License-Politeknik%20Negeri%20Banyuwangi-blue?style=flat-square)](#lisensi)

---

## Daftar Isi
1. [Ringkasan Eksekutif & Unduhan Dokumen Resmi](#1-ringkasan-eksekutif--unduhan-dokumen-resmi)
2. [Profil Mitra & Latar Belakang Masalah](#2-profil-mitra--latar-belakang-masalah)
3. [Fitur 1: Sistem Booking Online Mandiri (Customer-Facing)](#3-fitur-1-sistem-booking-online-mandiri-customer-facing)
4. [Fitur 2: Pemesanan Offline / Walk-In oleh Admin (Filament Panel)](#4-fitur-2-pemesanan-offline--walk-in-oleh-admin-filament-panel)
5. [Fitur 3: Ekosistem Rating & Testimoni Terverifikasi (Closed-Loop)](#5-fitur-3-ekosistem-rating--testimoni-terverifikasi-closed-loop)
6. [Matriks Komparasi: Online Booking vs Offline Walk-In](#6-matriks-komparasi-online-booking-vs-offline-walk-in)
7. [Matriks Pengujian Jaminan Mutu (QA Test Matrix & BDD Scenarios)](#7-matriks-pengujian-jaminan-mutu-qa-test-matrix--bdd-scenarios)
8. [Spesifikasi Teknologi & Arsitektur Perangkat Lunak](#8-spesifikasi-teknologi--arsitektur-perangkat-lunak)
9. [Panduan Instalasi & Menjalankan Sistem](#9-panduan-instalasi--menjalankan-sistem)
10. [Rekomendasi Rilis & Engineering Roadmap](#10-rekomendasi-rilis--engineering-roadmap)
11. [Lisensi & Hak Cipta](#11-lisensi--hak-cipta)

---

## 1. Ringkasan Eksekutif & Unduhan Dokumen Resmi

Dokumen ini merupakan hasil audit teknis menyeluruh, peninjauan tata kelola produk (*Product Management*), serta pengujian jaminan mutu (*Quality Assurance*) untuk platform **Sistem Informasi & Pemesanan Jasa Wrapping Kendaraan Bengkel Dantie Stiker**.

Untuk keperluan presentasi, telaah regulasi, maupun pembelajaran mandiri secara terstruktur, telah disediakan 2 berkas unduhan resmi:

* 📄 **Laporan Audit PDF Resmi (A4 Format):** [Audit_Alur_Sistem_Booking_Offline_Rating.pdf](file:///d:/laragon/www/informasi_pemesanan_wrapping/public/docs/Audit_Alur_Sistem_Booking_Offline_Rating.pdf)  
  *(Akses Browser: `http://127.0.0.1:8000/docs/Audit_Alur_Sistem_Booking_Offline_Rating.pdf`)*
* 📊 **Slide Presentasi PowerPoint Resmi (11 Slide 16:9 Widescreen):** [Audit_Alur_Sistem_Booking_Offline_Rating.pptx](file:///d:/laragon/www/informasi_pemesanan_wrapping/public/docs/Audit_Alur_Sistem_Booking_Offline_Rating.pptx)  
  *(Akses Browser: `http://127.0.0.1:8000/docs/Audit_Alur_Sistem_Booking_Offline_Rating.pptx`)*

---

## 2. Profil Mitra & Latar Belakang Masalah

**Mitra:** Bengkel Dantie Sticker (Banyuwangi, Jawa Timur)  
**Pengembang:** Tim Mahasiswa Proyek Berbasis Masalah (PBL) Politeknik Negeri Banyuwangi (POLIWANGI).

### Permasalahan Mitra Sebelum Sistem Dibangun:
1. **Informasi Layanan & Harga Tidak Terstandarisasi:** Calon pelanggan sulit mengetahui katalog jenis bahan wrapping (*glossy*, *matte*, *satin*, *chrome*, *paint protection film / PPF*) beserta kisaran biayanya.
2. **Ketiadaan Pengaturan Slot Pengerjaan (Overbooking):** Pemasangan stiker mobil membutuhkan ketelitian tinggi (2 hingga 4 hari kerja). Tanpa sistem pembatasan kuota, jadwal antar-mobil sering bertabrakan di workshop fisik.
3. **Pencatatan Pesanan & Kasir yang Terfragmentasi:** Pelanggan yang datang langsung (*walk-in*) ke bengkel dicatat menggunakan nota kertas manual, menyulitkan pelacakan status unit dan rekonsiliasi kasir bulanan.
4. **Ketiadaan Validasi Ulasan (Social Proof):** Testimoni pelanggan tidak terkumpul secara terpusat dan rawan manipulasi jika tidak dikaitkan dengan transaksi nyata.

---

## 3. Fitur 1: Sistem Booking Online Mandiri (Customer-Facing)

### A. Alur Pengguna & Logika Bisnis (Perspektif Product Manager)
Layanan wrapping adalah transaksi bernilai tinggi (*high-ticket item*). Kapasitas workshop fisik dibatasi konstan: **Maksimal 5 unit mobil per hari** untuk menjaga presisi dan kualitas pemasangan stiker.

```
+-------------------+     +---------------------+     +----------------------+
| 1. Pilih Paket    | --> | 2. Data Kendaraan   | --> | 3. Kalender Slot     |
| Katalog / Rekom   |     | Merk, Plat, Warna   |     | Cek Kuota Realtime/5 |
+-------------------+     +---------------------+     +----------------------+
                                                                 │
+-------------------+     +---------------------+                ▼
| 6. Invoice PDF &  | <-- | 5. Upload Bukti     | <-- +----------------------+
| Dashboard Pantau  |     | Transfer Bank / DP  |     | 4. Pilih Skema Bayar |
+-------------------+     +---------------------+     | DP (30%) / Lunas     |
                                                      +----------------------+
```

#### 6 Langkah Alur Pemesanan Online:
1. **Pemilihan Paket:** Pelanggan memilih paket wrapping (Full Body, Partial, Satin, PPF) dari katalog web interaktif atau tersinkronisasi otomatis dari keranjang belanja aktif.
2. **Identifikasi Kendaraan:** Input detail mobil mencakup nama/merk model (misal: *Honda Civic RS*), warna asli kendaraan, nomor plat polisi, dan catatan kerusakan awal (baret/penyok kecil).
3. **Pengecekan Kuota Tanggal:** Kalender memanggil `SlotKuotaService::checkQuota()`. Bila kuota 5/5 telah terpenuhi, tanggal terkunci (*disabled*) dan sistem otomatis mengarahkan ke hari berikutnya.
4. **Skema Pembayaran (Split Payment):**
   - **Tanda Jadi / Down Payment (DP):** Dihitung minimal 30% dari harga paket (atau Rp 500.000) untuk mengikat alokasi teknisi dan bahan material, sekaligus memitigasi risiko *no-show*.
   - **Pelunasan Penuh (Lunas):** Membayar 100% nominal di muka.
5. **Anti-Overbooking Concurrency Lock:** Request dieksekusi di dalam `DB::beginTransaction()` yang dikunci oleh `Cache::lock("booking_slot_{date}", 10)`.
6. **Upload Bukti & Penerbitan Invoice:** Pelanggan mengunggah struk transfer. Setelah admin menyetujui, invoice PDF resmi berbarcode dapat diunduh langsung.

### B. Finite State Machine (FSM) Transisi Status Booking
Implementasi berbasis PHP 8.1 Enum `App\Enums\BookingStatus`:

```
[ PENDING ] --------------------------> [ REJECTED ] (Ditolak Admin dengan Alasan Resmi)
     │
     ▼
[ CONFIRMED ]
     │
     ▼
[ AWAITING_PAYMENT ] -----------------> [ CANCELLED ] (Dibatalkan Customer/Admin)
     │
     ▼ (Customer Mengunggah Bukti Bayar)
[ PAYMENT_UPLOADED ]
     │
     ▼ (Admin Memvalidasi Masuknya Dana)
[ APPROVED ] (Jadwal Terkunci Sah di Kalender Bengkel)
     │
     ▼ (Mobil Masuk ke Area Workshop)
[ IN_PROGRESS ]
     │
     ▼ (Pemasangan Stiker Tuntas & Inspeksi Selesai)
[ COMPLETED ] ===> Hak Akses Ulasan (Rating) Resmi Aktif
```

---

## 4. Fitur 2: Pemesanan Offline / Walk-In oleh Admin (Filament Panel)

### A. Kebutuhan Operasional Kasir & Workshop (Perspektif PM)
Lebih dari 40% pelanggan datang langsung (*walk-in*) ke bengkel untuk berkonsultasi bahan fisik. Modul kasir di panel Filament (`App\Filament\Resources\Pesanans\PesananResource`) dirancang cepat, tangguh, dan tanpa birokrasi:

```
+-----------------------------------------------------------------------------+
|                     PANEL ADMIN FILAMENT: PESANAN WALK-IN                   |
+-----------------------------------------------------------------------------+
  │
  ├──► [OPSI 1]: Mode Tamu Cepat (Guest Walk-in Tanpa Akun)
  │              Cukup input Nama & Nomor WhatsApp aktif.
  │
  ├──► [OPSI 2]: Mode Pelanggan Terdaftar (Member)
  │              Pilih akun member lama atau buat akun baru via pop-up [+].
  │
  ├──► DATA FISIK KENDARAAN: Model Mobil, Warna Asli, Plat No, Estimasi Durasi.
  │
  ├──► KASIR & TAGIHAN:
  │    • Pilihan Metode: [ Tunai / Cash ] / [ Transfer Bank ] / [ Mesin EDC ]
  │    • Status Kasir: Langsung Terverifikasi (Status: VERIFIED)
  │
  ▼
[ EKSEKUSI TRANSAKSI ATOMIK (DB::transaction) ]
  ├── 1. Pesanan (Header: Kode PSN-OFF-XXXXXX, order_source: 'offline')
  ├── 2. DetailPesanan (Paket stiker + harga net)
  ├── 3. FormPesanan (Lembar kerja mekanik bengkel)
  └── 4. Pembayaran (Catatan kasir: Verified / Approved)
```

#### Keunggulan Arsitektur Walk-In:
1. **Pemisahan Sumber Transaksi:** Kolom `order_source = 'offline'` membedakan transaksi online web dan kasir langsung untuk akurasi pembukuan laba-rugi bengkel.
2. **Transaksi Multi-Tabel Atomik:** Satu kali klik tombol simpan mengeksekusi 4 tabel serentak dalam transaksi database ACID.
3. **Admin Override Kuota:** Sakelar `override_quota` disiapkan jika ada pesanan darurat/VIP yang harus dikerjakan tanpa terblokir kuota 5 mobil harian.

---

## 5. Fitur 3: Ekosistem Rating & Testimoni Terverifikasi (Closed-Loop)

### A. Kebijakan Kepercayaan Tertutup (*Closed-Loop Trust Policy*)
Untuk menghindari maraknya ulasan fiktif (*fake reviews*), sistem ulasan dipagari oleh aturan tata kelola produk (*product governance*):

```
+-----------------------+     (Pemasangan Stiker Tuntas)     +-----------------------+
| Mobil Selesai Dikerja | =================================> | Status: COMPLETED     |
+-----------------------+                                    +-----------------------+
                                                                         │
                               (Memicu Banner Dashboard)                 ▼
+-----------------------+     +------------------------+     +-----------------------+
| Admin Moderasi &      | <── | Sensor Kata Kasar      | <── | Form Rating Terbuka   |
| Balas di Filament     |     | (ContentGuard) + Foto  |     | 1-5 Bintang + 2 Foto  |
+-----------------------+     +------------------------+     +-----------------------+
```

1. **Gatekeeping Status Selesai:** Customer **hanya dapat mengulas** apabila pesanan/booking telah berstatus `COMPLETED` atau `SELESAI`.
2. **Idempotensi & Update (Anti-Spam):** 1 transaksi = 1 ulasan. Pengiriman form ulasan berikutnya akan memperbarui (*UPDATE*) record lama, bukan membuat record ganda.
3. **Smart Reminder Banner:** Modul `_review-reminder-banner.blade.php` otomatis menyapa pelanggan di dashboard jika ada unit mobil yang telah selesai namun belum diulas.
4. **Bukti Visual (Visual Proof):** Pelanggan dapat melampirkan maksimal 2 foto hasil wrapping (maks. 5MB, format JPG/PNG/WEBP).
5. **Sensor Kata Kasar (ContentGuard):** `RatingService::assertCleanContent()` mendeteksi dan membatalkan submit ulasan yang memuat ujaran kotor/kasar.
6. **Moderasi & Balasan Resmi Admin:** Melalui `RatingResource`, admin dapat menyematkan balasan resmi (`balasan_admin`) serta menentukan apakah testimoni layak ditampilkan ke landing page publik.

---

## 6. Matriks Komparasi: Online Booking vs Offline Walk-In

| Parameter Arsitektur | Booking Online Mandiri (Customer) | Pesanan Walk-In Kasir (Admin Workshop) |
| :--- | :--- | :--- |
| **Inisiator Input** | Customer melalui website publik | Admin kasir melalui Filament panel `/admin` |
| **Persyaratan Akun** | Wajib Register, Login & Email Verified | Fleksibel: Mode Tamu (Guest) atau Member Terdaftar |
| **Batas Kuota Slot** | Ketat Maksimal 5 Slot/Hari (Cache Lock) | Kuota terpantau, tersedia opsi *Admin Override* |
| **Metode Pembayaran** | Transfer Bank / E-Wallet + Upload Struk | Tunai Fisik (Cash), Mesin EDC Kartu, Transfer |
| **Verifikasi Kasir** | 2 Tahap: User Upload &rarr; Admin Validasi | Instan di kasir toko (Status langsung *VERIFIED*) |
| **Format Kode Unik** | `BKG-YYYYMMDD-XXXXX` | `PSN-OFF-XXXXXX` |
| **Dokumen Bukti** | Invoice PDF Resmi berbarcode (unduh mandiri) | Surat Perintah Kerja (SPK) & Kwitansi Kasir Toko |
| **Pemicu Hak Rating** | Otomatis setelah booking status `COMPLETED` | Otomatis setelah pesanan status `SELESAI` |

---

## 7. Matriks Pengujian Jaminan Mutu (QA Test Matrix & BDD Scenarios)

Seluruh 83 pengujian otomatis (*automated test suite*) telah dieksekusi dengan status **100% HIJAU (PASS)**:

| ID Uji | Fitur | Skenario Pengujian (Given - When - Then) | Hasil Audit |
| :--- | :--- | :--- | :--- |
| **TC-BKG-001** | Booking | **Given** kuota tersisa 1 slot, **When** 2 user submit checkout di milidetik bersamaan, **Then** 1 sukses dan 1 dilempar `SlotPenuhException` via Cache Lock. | <span style="color:green;font-weight:bold;">PASS</span> |
| **TC-BKG-002** | Booking | **Given** akun belum verifikasi email, **When** mencoba checkout booking, **Then** sistem me-redirect ke `verification.notice`. | <span style="color:green;font-weight:bold;">PASS</span> |
| **TC-BKG-003** | Booking | **Given** form upload bukti bayar, **When** payload file berbahaya diunggah, **Then** ditolak oleh validasi MIME type & size 5MB. | <span style="color:green;font-weight:bold;">PASS</span> |
| **TC-BKG-004** | Booking | **Given** status `pending`, **When** injeksi transisi ilegal ke `completed`, **Then** ditolak oleh validasi Enum FSM. | <span style="color:green;font-weight:bold;">PASS</span> |
| **TC-OFF-001** | Walk-In | **Given** pelanggan offline tanpa akun, **When** admin memilih mode tamu, **Then** pesanan terbuat dengan `id_user = NULL` dan kode `PSN-OFF-`. | <span style="color:green;font-weight:bold;">PASS</span> |
| **TC-OFF-002** | Walk-In | **Given** pembayaran tunai di kasir toko, **When** admin memilih cash, **Then** record pembayaran langsung berstatus `VERIFIED`. | <span style="color:green;font-weight:bold;">PASS</span> |
| **TC-OFF-003** | Walk-In | **Given** kegagalan sistem di tengah input tabel, **When** exception terjadi, **Then** `DB::transaction` membatalkan seluruh operasi secara atomik. | <span style="color:green;font-weight:bold;">PASS</span> |
| **TC-OFF-004** | Walk-In | **Given** user non-admin, **When** mencoba mengakses `/admin/pesanans/create`, **Then** sistem memblokir dengan respon HTTP 403 Forbidden. | <span style="color:green;font-weight:bold;">PASS</span> |
| **TC-RAT-001** | Rating | **Given** booking masih berstatus `in_progress`, **When** user mengirim request rating, **Then** sistem merespon HTTP 403 Forbidden. | <span style="color:green;font-weight:bold;">PASS</span> |
| **TC-RAT-002** | Rating | **Given** ulasan memuat kata kotor/kasar, **When** submit ulasan, **Then** `ContentGuard` memblokir dan menampilkan warning. | <span style="color:green;font-weight:bold;">PASS</span> |
| **TC-RAT-003** | Rating | **Given** pelanggan sudah pernah memberi bintang, **When** mengirim ulasan baru, **Then** sistem melakukan `UPDATE` bukan membuat baris ganda. | <span style="color:green;font-weight:bold;">PASS</span> |
| **TC-RAT-004** | Rating | **Given** modal rating, **When** user mengunggah 3 foto (melebihi batas 2), **Then** form validator menolak secara otomatis. | <span style="color:green;font-weight:bold;">PASS</span> |
| **TC-RAT-005** | Rating | **Given** pesanan milik User A, **When** User B mencoba memberi rating dengan ID User A, **Then** dicegat oleh proteksi query IDOR. | <span style="color:green;font-weight:bold;">PASS</span> |

---

## 8. Spesifikasi Teknologi & Arsitektur Perangkat Lunak

* **Framework Backend:** Laravel 12.x (PHP ^8.2)
* **Admin Panel:** Filament PHP 5.x (Panel Builder, Resource, Form Schema, Table Actions)
* **Frontend UI:** Blade Templating, Tailwind CSS v3.1, Alpine.js v3.4, Vite v6.0
* **Mesin Dokumen & Cetak:** `barryvdh/laravel-dompdf` (Invoice PDF & Laporan Audit)
* **Generator Presentasi:** Python 3.13 (`python-pptx`)
* **Manajemen Peran & Hak Akses:** Spatie Laravel Permission (Roles: `admin`, `user`)
* **Pengujian Mutu:** Pest PHP 4.x & PHPUnit (83 Feature/Unit Test Suite)
* **Penyimpanan Berkas:** Symlinked Storage Disk `public/` (Bukti Transfer, Struk, Foto Rating)

---

## 9. Panduan Instalasi & Menjalankan Sistem

### Prasyarat
* PHP >= 8.2 (dengan ekstensi `pdo_sqlite`, `pdo_mysql`, `gd`, `zip`, `mbstring`)
* Composer
* Node.js & NPM
* Python 3.x (opsional, untuk rebuild presentasi PowerPoint)

### Langkah Instalasi
1. **Clone repositori dan pasang dependensi backend:**
   ```bash
   composer install
   ```
2. **Pasang dependensi frontend:**
   ```bash
   npm install
   ```
3. **Konfigurasi Environment:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
4. **Migrasi Database & Seeder:**
   ```bash
   php artisan migrate --seed
   php artisan storage:link
   ```
5. **Jalankan Server Development:**
   ```bash
   # Jalankan web server Laravel
   php artisan serve

   # Pada terminal kedua, jalankan Vite compiler
   npm run dev
   ```
6. **Akses Aplikasi:**
   - Halaman Publik: `http://localhost:8000`
   - Panel Admin Filament: `http://localhost:8000/admin`

---

## 10. Rekomendasi Rilis & Engineering Roadmap

1. **Otomasi WhatsApp Gateway (Fonnte / Twilio):** Pengiriman notifikasi WhatsApp instan saat unit mobil masuk pengerjaan (`IN_PROGRESS`) dan saat siap diambil (`COMPLETED`).
2. **Midtrans Snap Integration:** Mengurangi beban kerja verifikasi bukti transfer manual melalui QRIS / Virtual Account yang auto-verifikasi via callback webhook.
3. **Filter Testimoni Berdasarkan Jenis Mobil:** Menampilkan galeri testimoni di landing page yang dapat difilter per model mobil (misal: *Honda HR-V*, *Toyota Fortuner*).

---

## 11. Lisensi & Hak Cipta

Proyek dikembangkan dalam rangka Tugas PBL Mahasiswa **Politeknik Negeri Banyuwangi (POLIWANGI)** bermitra dengan **Bengkel Dantie Sticker**.

Hak Cipta &copy; 2026 Bengkel Dantie Sticker & Politeknik Negeri Banyuwangi. Seluruh hak cipta dilindungi undang-undang.
