# 🎁 Informasi Pemesanan Wrapping — Dantie Sticker

Sistem informasi pemesanan jasa wrapping/stiker kendaraan berbasis web. Dibangun dengan **Laravel 12**, **Filament v5**, **Blade + Tailwind CSS + Alpine.js**, **Spatie Permission**, dan **Laravel Sanctum**.

---

## 📋 Daftar Isi

- [Tech Stack & Tools (Apa Saja yang Dipakai?)](#tech-stack--tools)
- [Struktur Folder & Pemetaan Fitur (File Mana untuk Fitur Apa?)](#struktur-folder--pemetaan-fitur)
- [Metode & Pola Arsitektur (Cara Kerja Sistem)](#metode--pola-arsitektur)
- [Database & Normalisasi 3NF (Apakah Database Sudah Benar?)](#database--normalisasi-3nf)
- [Keamanan Sistem (Benteng Pertahanan)](#keamanan-sistem)
- [Review Sistem — Temuan & Rekomendasi](#-review-sistem--temuan--rekomendasi-dari-it-analys)
- [Cara Install & Jalankan](#persyaratan-sistem)
- [Tim Pengembang](#tim-pengembang)

---

## Tech Stack & Tools

> **Tech Stack** = kumpulan teknologi yang dipakai untuk membangun aplikasi. Ibarat membangun rumah, butuh semen, bata, cat, alat ukur, dll. Berikut alat-alat yang dipakai di proyek ini:

### 🖥️ Backend (Bagian Server / Otak)

| Nama Tools | Fungsinya (buat apa?) | Manfaatnya (enaknya apa?) | Kenapa Pilih Ini? |
|---|---|---|---|
| **Laravel 12** | Framework PHP (kerangka kerja) untuk bikin aplikasi web. Ngatur routing, database, login, dll | Coding cepat, fitur lengkap, komunitas besar | Paling populer di PHP, dokumentasi rapi, cocok buat aplikasi kaya gini |
| **PHP ^8.2** | Bahasa pemrograman yang jalan di server | Bisa ngomong sama database, ngolah data, render halaman | Standar industri web, versi terbaru udah punya fitur modern |
| **Filament v5** | Generator admin panel otomatis | Tinggal bikin model, admin panel langsung jadi (CRUD, tabel, form, grafik) | Hemat waktu 80%, tinggal setting dikit langsung keluar dashboard keren |
| **Spatie Permission v7** | Ngatur siapa punya akses apa (Role & Permission) | Bisa bikin role Admin dan User, atur izin akses per fitur | Paling terkenal di ekosistem Laravel, tinggal pakai |
| **Laravel Sanctum** | API token — kunci akses untuk aplikasi mobile/SPA | Kasih token ke user setelah login, token dipakai buat akses API | Ringan, built-in, gak perlu ribet OAuth |
| **Event & Listener** | Sistem trigger-response. "Kalau A terjadi, lakukan B" | Misal: "Kalau order baru masuk (event), kirim email ke admin (listener)" | Pisahin logika, gampang ditambah fitur baru tanpa ganggu yang lama |
| **Queue** | Antrian kerja background | Kirim email di background, user gak perlu nunggu loading | Aplikasi tetap responsif, gak lemot |
| **Eloquent ORM** | Penterjemah database ke kode PHP | Nulis `User::find(1)` bukan `SELECT * FROM users WHERE id=1` | Cepet, gak perlu hafal SQL, bisa relasi tabel gampang |
| **Pest PHP** | Alat testing (tes otomatis) | Nulis tes kayak ngomong: `expect(true)->toBeTrue()` | Lebih enak dibaca daripada PHPUnit biasa |

### 🎨 Frontend (Bagian Tampilan / Wajah)

| Nama Tools | Fungsinya | Manfaatnya | Kenapa Pilih Ini? |
|---|---|---|---|
| **Blade** | Template engine Laravel, bikin halaman HTML | Bisa pakai `@if`, `@foreach`, komponen, layout. Otomatis proteksi dari XSS | Sudah bawaan Laravel, gak perlu pasang tambahan |
| **Tailwind CSS v3** | Framework CSS utility-first (tinggal pakai class siap pakai) | Nulis `<div class="bg-blue-500 text-white p-4">` langsung jadi biru | Gak perlu mikir nama class CSS, hasil konsisten, ukuran file kecil |
| **Alpine.js v3** | JavaScript ringan buat interaksi simpel | Modal, dropdown, toggle tanpa perlu Vue/React | Lebih enteng, cocok buat yang gak butuh SPA berat |
| **Vite** | Alat bundling CSS/JS + hot reload | Reboot otomatis waktu ganti kode, loading cepet | 10-20x lebih kencang dari Laravel Mix |
| **Axios** | HTTP client buat panggil API dari JavaScript | Gampang panggil backend, otomatis kirim token CSRF | Standar di Laravel, udah include di Breeze |

### 🏗️ Infrastructure (Pondasi)

| Nama Tools | Fungsinya | Manfaatnya | Kenapa Pilih Ini? |
|---|---|---|---|
| **MySQL** | Database utama | Nyimpen data, query cepet, support indexing | Udah terkenal, gratis, cocok production |
| **SQLite** | Database ringan (development + testing) | File doang, gak perlu install MySQL cuma buat testing | Praktis buat local development |
| **Git & GitHub** | Version control — nyimpen history coding | Bisa balik ke versi lama, kerja bareng tim, branch masing-masing | Standar industri, wajib buat kolaborasi tim |

---

## Struktur Folder & Pemetaan Fitur

> **Struktur folder itu kayak lemari arsip.** Setiap laci (folder) punya isi yang beda. Berikut peta lengkapnya biar kamu tahu "file ini buat fitur apa" tanpa harus bongkar satu-satu.

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                         📁 INFORMASI_PEMESANAN_WRAPPING                     │
├─────────────────────────────────────────────────────────────────────────────┤
│  📁 app/                     → 🧠 KODE INTI (Otak Aplikasi)                │
│  📁 routes/                  → 🛤️ ALAMAT URL (Peta Jalan)                  │
│  📁 database/                → 🗃️ DATABASE (Gudang Data)                   │
│  📁 resources/views/         → 👀 TAMPILAN (Wajah Aplikasi)                │
│  📁 resources/js/            → ⚡ JAVASCRIPT (Gerak-gerik)                 │
│  📁 config/                  → ⚙️ PENGATURAN (Setelan)                     │
│  📁 public/                  → 🌍 PINTU MASUK (Public Access)              │
│  📁 tests/                   → 🧪 PENGUJIAN (Quality Control)              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 🧠 `app/` — Otak Aplikasi (Folder Terpenting)

```
📁 app/
│
├── 📁 Enums/               → 🏷️ KATEGORI TETAP (biar gak salah ketik)
│   ├── OrderStatus.php     →   Status pesanan: "menunggu konfirmasi" → "dibayar" → "diproses" → "selesai"
│   ├── PaymentStatus.php   →   Status bayar: pending, verified, rejected
│   ├── PaymentMethod.php   →   Cara bayar: transfer bank, e-wallet, cash
│   └── NotificationType.php → Tipe notif: email, SMS, in-app, push
│
├── 📁 Events/              → 🔔 TANDA (Trigger). Ibarat bel pintu
│   ├── OrderCreated.php    →   "Wei, ada order baru masuk!"
│   ├── OrderConfirmed.php  →   "Order udah dikonfirmasi admin!"
│   ├── PaymentUploaded.php →   "Customer upload bukti bayar!"
│   ├── PaymentVerified.php →   "Pembayaran udah diverifikasi!"
│   ├── OrderCompleted.php  →   "Pesanan selesai!"
│   └── OrderRejected.php   →   "Pesanan ditolak :("
│
├── 📁 Listeners/           → 👂 RESPONDEN (Yang denger bel terus bertindak)
│   ├── SendOrderConfirmationEmail.php    → Kirim email "Pesananmu masuk"
│   ├── SendOrderCreatedToAdmin.php       → Kirim notif ke admin
│   ├── NotifyPaymentRequired.php         → Kirim notif "Bayar dulu yuk!"
│   ├── SendPaymentUploadedToAdmin.php    → "Admin, ada yang upload bukti!"
│   ├── NotifyOrderProcessingStarted.php  → "Pesananmu mulai dikerjain!"
│   ├── NotifyOrderCompleted.php          → "Pesanan selesai, senang ya!"
│   └── NotifyOrderRejection.php          → "Maaf, pesanan ditolak karena..."
│
├── 📁 Http/
│   ├── 📁 Controllers/
│   │   ├── 📁 Auth/                     → 🔐 URUSAN LOGIN (9 file)
│   │   │   ├── Login                    →   Proses login
│   │   │   ├── Register                 →   Proses daftar
│   │   │   ├── VerifyEmail              →   Verifikasi email
│   │   │   ├── ForgotPassword           →   Lupa password
│   │   │   ├── ResetPassword            →   Ganti password
│   │   │   └── ConfirmPassword          →   Konfirmasi password (buat hapus akun)
│   │   │
│   │   ├── 📁 Api/                      → 📡 ENDPOINT UNTUK MOBILE/SPA (16 file)
│   │   │   ├── AuthController.php       →   Login/register API
│   │   │   ├── Layanan*.php             →   CRUD layanan via API
│   │   │   ├── Keranjang*.php           →   CRUD keranjang via API
│   │   │   ├── Pesanan*.php             →   CRUD pesanan via API
│   │   │   ├── Pembayaran*.php          →   Upload & verifikasi bayar via API
│   │   │   ├── Notifikasi*.php          →   Notifikasi via API
│   │   │   └── Admin/                   →   Dashboard + kelola pesanan + verifikasi bayar
│   │   │
│   │   ├── CustomerController.php       → 🧑‍💼 Halaman customer
│   │   ├── DashboardController.php      → 📊 Dashboard utama
│   │   ├── GaleriController.php         → 🖼️ Galeri pekerjaan
│   │   ├── KeranjangController.php      → 🛒 Keranjang (via web biasa)
│   │   ├── PesananController.php        → 📋 Pesanan (checkout, invoice)
│   │   ├── ProfileController.php        → 👤 Edit profil
│   │   └── LaporanController.php        → 📈 Laporan penjualan + cetak PDF
│   │
│   ├── 📁 Middleware/
│   │   └── RoleMiddleware.php           → 🛡️ PENJAGA PINTU. Cek role user
│   │
│   ├── 📁 Requests/                     → ✅ VALIDASI. "Data yang masuk udah bener?"
│   │   ├── LoginRequest.php             →   Validasi pas login
│   │   ├── RegisterRequest.php          →   Validasi pas daftar
│   │   ├── CheckoutRequest.php          →   Validasi pas checkout
│   │   └── ProfileUpdateRequest.php     →   Validasi edit profil
│   │
│   └── 📁 Resources/                    → 📦 TRANSFORMER. "Bentuk data API biar rapi"
│       ├── LayananResource.php          →   Ubah data layanan ke JSON
│       ├── KeranjangResource.php        →   Ubah data keranjang ke JSON
│       ├── PesananResource.php          →   Ubah data pesanan ke JSON
│       ├── PembayaranResource.php       →   Ubah data bayar ke JSON
│       ├── NotifikasiResource.php       →   Ubah data notif ke JSON
│       └── UserResource.php             →   Ubah data user ke JSON
│
├── 📁 Models/               → 📊 MODEL. "Template data di database"
│   ├── User.php             →   Data user
│   ├── Layanan.php          →   Data layanan wrapping
│   ├── Keranjang.php        →   Keranjang belanja (parent)
│   ├── DetailKeranjang.php  →   Isi keranjang (child)
│   ├── Pesanan.php          →   Data pesanan
│   ├── DetailPesanan.php    →   Isi pesanan
│   ├── FormPesanan.php      →   Form detail (kendaraan, jadwal)
│   ├── Pembayaran.php       →   Data pembayaran
│   ├── Notifikasi.php       →   Data notifikasi in-app
│   ├── Galeri.php           →   Data galeri
│   ├── ProfilPerusahaan.php →   Data CMS landing page
│   └── Traits/              →   Kode yang dipakai ulang
│
├── 📁 Services/             → ⚙️ LOGIKA BISNIS. "Di sini aturan main bisnis"
│   ├── CartService.php      →   Aturan keranjang: max 3 item, cek duplikat
│   ├── KeranjangService.php →   Operasi keranjang: nambah, hapus, kosongin
│   ├── PesananService.php   →   Checkout + update status (dicek state machine)
│   ├── PembayaranService.php → Upload bukti + verifikasi (pake locking)
│   └── NotifikasiService.php → Kirim notif lewat berbagai channel
│
├── 📁 Policies/             → 🛡️ ATURAN AKSES. "Siapa boleh ngapa-in?"
│   ├── KeranjangPolicy.php  →   Cuma pemilik keranjang yang bisa lihat/edit
│   ├── PesananPolicy.php    →   Admin: semua. User: cuma punya sendiri
│   └── UserPolicy.php       →   Cuma admin yang bisa kelola user
│
├── 📁 Traits/
│   └── ApiResponse.php      → 📮 FORMAT API SAMA. Semua API kasih response {status, message, data}
│
└── 📁 Filament/             → 🎛️ ADMIN PANEL. "Dashboard untuk admin"
    ├── Pages/LaporanPenjualan.php      → Laporan penjualan
    ├── Widgets/
    │   ├── OrderStatsWidget.php        → Kotak statistik (total pesanan, pendapatan)
    │   ├── RecentOrdersWidget.php      → Tabel pesanan terbaru
    │   └── RevenueChartWidget.php      → Grafik pendapatan
    └── Resources/
        ├── BerandaResource.php         → CMS halaman beranda
        ├── TentangKamiResource.php     → CMS halaman tentang kami
        ├── HalamanLayananResource.php  → CMS halaman layanan
        ├── HalamanGaleriResource.php   → CMS halaman galeri
        ├── Layanans/                   → CRUD layanan
        ├── Galeris/                    → CRUD galeri
        ├── Pesanans/                   → Manajemen pesanan
        ├── Users/                      → Manajemen user
        └── ProfilPerusahaans/          → Edit profil perusahaan
```

### 🛤️ `routes/` — Peta Jalan (URL)

```
📁 routes/
├── web.php        → 🌐 URL untuk halaman web biasa (Blade). Contoh: /beranda, /katalog, /dashboard
├── api.php        → 📡 URL untuk API (dipanggil mobile/JS). Contoh: /api/layanan, /api/pesanan
├── auth.php       → 🔐 URL untuk login/register. Contoh: /login, /register, /forgot-password
└── console.php    → 💻 Perintah Artisan. Contoh: php artisan some:command
```

### 🗃️ `database/` — Gudang Data

```
📁 database/
├── migrations/       → 📜 SKEMA DATABASE. 44 file yang bikin tabel-tabel
│   ├── users                  → Tabel users
│   ├── profil_perusahaans     → Tabel CMS (100 kolom!)
│   ├── layanans               → Tabel layanan wrapping
│   ├── keranjangs             → Tabel keranjang
│   ├── detail_keranjangs      → Tabel isi keranjang
│   ├── pesanans               → Tabel pesanan
│   ├── detail_pesanans        → Tabel isi pesanan
│   ├── form_pesanans          → Tabel form order (kendaraan, jadwal)
│   ├── pembayarans            → Tabel pembayaran
│   ├── notifikasis            → Tabel notifikasi
│   ├── galeris                → Tabel galeri
│   └── permission_tables      → Tabel role & permission (Spatie)
│
└── seeders/         → 🌱 BIBIT DATA AWAL
    ├── RolesSeeder            → Bikin role: admin, user
    ├── PermissionsSeeder      → Bikin 12 izin akses
    ├── UserSeeder             → Bikin admin default
    └── ProfilPerusahaanSeeder → Isi data profil awal
```

### 👀 `resources/views/` — Tampilan (Wajah Aplikasi)

```
📁 resources/views/
├── layouts/               → 🖼️ LAYOUT INDUK (template dasar semua halaman)
├── components/            → 🧩 KOMPONEN (navbar, dropdown, modal — 16 komponen)
├── auth/                  → 🔐 Halaman: login, register, lupa password
│
├── landing/               → 🌐 HALAMAN DEPAN (Publik — bisa lihat tanpa login)
│   ├── beranda/           →   Halaman utama (hero, keunggulan, CTA langkah)
│   ├── katalog/           →   Daftar layanan wrapping
│   ├── layanan/           →   Detail layanan
│   ├── galeri/            →   Galeri hasil pekerjaan
│   ├── tentang-kami/      →   Tentang perusahaan
│   └── profil/            →   Profil perusahaan
│
├── dashboard/             → 📊 HALAMAN SETELAH LOGIN
│   ├── customer/          →   Halaman customer
│   │   ├── dashboard/     →     Dashboard utama customer
│   │   ├── keranjang/     →     Keranjang belanja
│   │   └── pesanan/       →     Daftar & detail pesanan
│   └── admin/laporan/     →   Laporan penjualan (print PDF)
│
└── profile/               → 👤 Edit profil & ganti password
```

### ⚡ `resources/js/` — JavaScript (Gerak-gerik)

```
📁 resources/js/
├── app.js                 → Init Alpine.js
├── bootstrap.js           → Init Axios + CSRF token
├── api.js                 → Konfigurasi panggilan API
├── components/cart.js     → Interaksi keranjang
└── utils/
    ├── formatting.js      → Format harga, tanggal
    ├── storage.js         → LocalStorage helper
    └── ui.js              → Utility UI umum
```

---

## Metode & Pola Arsitektur

> **Pola arsitektur** = cara kita mengatur kode biar rapi, gampang dirawat, dan gak pusing kalau mau nambah fitur. Ibaratnya: kalau dapur berantakan, masak jadi susah. Pola ini bikin "dapur kode" tetap rapi.

---

### 1. MVC (Model-View-Controller) —️⃣  Dasar Laravel

**Analogi:** Restoran
- **Model** = Dapur + Bahan masakan (data & database)
- **View** = Piring yang disajiin ke pelanggan (tampilan HTML)
- **Controller** = Pelayan yang terima pesanan, kasih ke dapur, terus anter ke meja (pemediasi)

**Fungsinya:** Misahin urusan data, tampilan, dan logika biar gak campur aduk.

**Kenapa Dipakai?** Ini pola standar Laravel. Kalau semua kode ditulis di satu file, ribet ngurusnya. Dengan MVC, developer frontend bisa urus View doang, developer backend urus Model & Controller.

---

### 2. Service Layer ➡️  Pisahin Logika Bisnis

**Analogi:** Koki spesialis
- **Controller** = Pelayan restoran (terima order, anter makanan)
- **Service** = Koki di dapur (masak, atur bumbu, tentuin resep)

**Fungsinya:** Controller cukup panggil service. Login bisnis yang rumit (checkout, update status, verifikasi bayar) dikerjain di Service.

**Manfaat:**
- Controller jadi tipis (gak penuh kode)
- Service bisa dipakai Web Controller & API Controller sekaligus
- Kalau ada bug di logika checkout, cukup cek 1 file (PesananService.php)
- Testing lebih mudah — test service langsung tanpa perlu HTTP

**Contoh di kode:**
```php
// Controller (tipis):
public function checkout(Request $request) {
    $pesanan = $this->pesananService->checkout(Auth::id(), $request->all());
    return redirect()->route('pesanan.show', $pesanan);
}

// Service (isi semua logika):
public function checkout($userId, $data) {
    // 1. Cek keranjang kosong?
    // 2. Lock database biar gak dobel checkout
    // 3. Generate kode pesanan
    // 4. Hitung total harga
    // 5. Simpan pesanan + detail + form
    // 6. Kosongin keranjang
    // 7. Kirim event "OrderCreated"
}
```

---

### 3. Event-Driven (Event & Listener) 👂  Sistem Bel Pintu

**Analogi:** Bel pintu di rumah
- **Event** = Seseorang pencet bel (terjadi sesuatu)
- **Listener** = Kamu yang denger bel terus:
  - Kalau bel 1x: buka pintu
  - Kalau bel 2x: ambil paket
  - Kalau bel 3x: terima tamu

**Fungsinya:** Misahin "yang memicu" (event) dari "yang merespon" (listener).

**Alur Event di Sistem Ini:**
```
📦 Order Masuk (Event: OrderCreated)
   → Kirim email ke customer (Listener 1)
   → Kirim notif ke admin (Listener 2)

✅ Admin Konfirmasi (Event: OrderConfirmed)
   → Kirim notif ke customer "segera bayar" (Listener)

📤 Customer Upload Bukti (Event: PaymentUploaded)
   → Kirim notif ke admin "cek pembayaran" (Listener)

✔️ Admin Verifikasi (Event: PaymentVerified)
   → Kirim notif "pesanan mulai diproses" (Listener)

🎉 Pesanan Selesai (Event: OrderCompleted)
   → Kirim notif "pesanan selesai" (Listener)

❌ Pesanan Ditolak (Event: OrderRejected)
   → Kirim notif + alasan penolakan (Listener)
```

**Kenapa Pake Ini?**
- Mau nambah fitur "kirim WhatsApp kalau order baru"? Tinggal bikin Listener baru, gak perlu utak-atik kode yang sudah ada
- Listener bisa dijalankan di background (queue) biar gak bikin loading
- Kode lebih terstruktur: 1 event, banyak listener → gampang dirawat

---

### 4. State Machine 🔄  Aturan Main Status Pesanan

**Analogi:** Lampu lalu lintas
- Lampu merah → ijo → kuning → merah (urutannya tetap, gak bisa lompat)
- Gak mungkin langsung merah ke "ngelang" atau ijo ke "stop"

**Fungsinya:** Status pesanan cuma bisa berubah sesuai aturan. Gak bisa lompat-lompat sembarangan.

**Diagram Status Pesanan:**
```
menunggu konfirmasi admin
    │
    ├── (admin setuju) → menunggu pembayaran
    │                       │
    │                       ├── (customer upload bukti) → menunggu verifikasi
    │                       │                                  │
    │                       │                                  ├── (admin verif) → dikonfirmasi → diproses → SELESAI ✅
    │                       │                                  └── (ditolak) → DITOLAK ❌
    │                       │
    │                       └── (ditolak admin) → DITOLAK ❌
    │
    └── (ditolak admin) → DITOLAK ❌
```

**Contoh aturan:**
- Dari "menunggu konfirmasi" → boleh ke "menunggu bayar" atau "ditolak"
- Dari "menunggu bayar" → cuma boleh ke "menunggu verifikasi" atau "ditolak"
- Kalau udah "selesai" → udah, final, gak bisa diubah lagi

**Kenapa Pake Ini?**
- Mencegah kesalahan: gak bakal ada pesanan tiba-tiba statusnya "selesai" padahal belum dibayar
- Aturan bisnis terpusat di 1 file (OrderStatus.php), gak tersebar
- Kode jadi "self-documenting" — orang tinggal baca file ini buat tahu alur pesanan

---

### 5. Repository via Eloquent 🗄️  Cara Ngobrol dengan Database

**Analogi:** Penerjemah bahasa
- Kamu ngomong Indonesia ke penerjemah, dia omong Inggris ke bule
- Kamu nulis `Pesanan::where('status', 'selesai')->get()` → Eloquent terjemahin jadi `SELECT * FROM pesanans WHERE status = 'selesai'`

**Fungsinya:** Abstraksi database. Developer gak perlu nulis SQL mentah.

**Manfaat:**
- Cepet: nulis query pakai PHP, gak perlu hafal SQL
- Aman: otomatis proteksi SQL Injection (parameter binding)
- Relasi gampang: `$pesanan->user` (dapetin data user dari pesanan)

---

### 6. API Resource 📦  Merapikan Data Sebelum Dikirim

**Analogi:** Kado
- Isi kado sebenarnya (data mentah dari database) dibungkus rapi (API Resource) sebelum dikasih ke orang (dikirim ke mobile app)

**Fungsinya:** Transformasi data sebelum dikembalikan sebagai JSON. Kolom sensitif disembunyiin, format tanggal dirapihin, data gak penting dibuang.

**Manfaat:**
- API response konsisten: semua endpoint kasih format `{status, message, data}`
- Aman: password, token gak ikut terkirim
- Bisa atur "kalau admin, tampilkan semua. Kalau user, tampilkan sebagian"

---

### 7. Singleton (Profil Perusahaan) 👑  Cukup Satu aja

**Analogi:** Presiden. Cuma ada 1 presiden, gak perlu bikin presiden baru tiap kali ada rapat.

**Fungsinya:** Profil perusahaan cuma 1 baris di database. Disimpan di cache supaya gak query database tiap kali halaman dibuka.

**Manfaat:**
- Halaman loading cepet: data profil dari cache, bukan query DB
- Cache direset otomatis kalau admin edit profil

---

### 8. Pessimistic Locking 🔒  Kunci Biar Gak Dobelan

**Analogi:** Parkiran mobil
- Parkiran khusus 1 mobil. Kalau ada yang masuk, pintu parkiran dikunci — mobil lain harus nunggu sampai yang pertama keluar.

**Fungsinya:** Mencegah 2 orang checkout di waktu bersamaan (race condition). Kalau gak dikunci, bisa terjadi:
- User A dan User B bayar barang yang sama
- Stok jadi minus
- Double pesanan

**Implementasi:**
```php
$keranjang = Keranjang::lockForUpdate()  // Kunci baris ini!
    ->where('id_user', $userId)
    ->firstOrFail();
// User lain yang checkout harus nunggu ini selesai
```

---

### 9. Form Request ✅  Satpam Data Masuk

**Analogi:** Satpam di mall. Sebelum masuk, diperiksa:
- Bawa KTP? (field nama wajib)
- Umur cukup? (format no_hp bener)
- Bawa barang terlarang? (XSS/script berbahaya)

**Fungsinya:** Validasi data dari user sebelum diproses. Kalau ada yang salah, langsung ditolak.

**Manfaat:**
- Controller gak penuh validasi (30 baris `$request->validate()`)
- Validation rule bisa dipakai ulang
- Error message konsisten

---

### 10. Policy 🚧  Karcis Masuk

**Analogi:** Karcis konser
- Backstage: cuma artis & kru (admin)
- VIP: tiket VIP (pemilik data)
- Umum: tiket biasa (user biasa)
- Gak punya tiket = gak boleh masuk

**Fungsinya:** Aturan siapa boleh lihat/edit data apa.

**Aturan di Sistem Ini:**
| Policy | Aturan |
|---|---|
| KeranjangPolicy | Kamu cuma bisa lihat/edit keranjangmu sendiri |
| PesananPolicy | Admin bisa lihat semua pesanan. Kamu cuma punyamu sendiri |
| UserPolicy | Cuma admin yang bisa kelola data user |

---

### 11. Caching 💨  Biar Cepet

**Analogi:** Kulkas vs pasar
- Tiap kali masak, kamu ke pasar beli bumbu → capek (query DB tiap request)
- Beli bumbu sekali, simpan di kulkas → tinggal ambil (cache)

**Fungsinya:** Nyimpen data yang jarang berubah di memory, biar gak perlu query database berulang-ulang.

**Data yang di-cache:**
| Data | Dicache berapa lama? | Kapan direset? |
|---|---|---|
| Profil perusahaan | Forever (sampai diubah) | Saat admin edit profil |
| Daftar layanan | Forever | Saat admin tambah/hapus layanan |
| Galeri | Forever | Saat admin upload/hapus galeri |

**Dampak:** Halaman landing (beranda, katalog) loading super cepat karena data di-cache.

---

### 12. Trait ♻️  Kode yang Dipake Berulang

**Analogi:** Cetakan kue. Kamu bikin 1 cetakan, bisa dipake buat bikin 100 kue dengan bentuk yang sama.

**Fungsinya:** Method yang sama dipakai di banyak file. Contoh `ApiResponse` trait dipakai di SEMUA API controller — semua API kasih response dengan format yang seragam.

---

### 13. Chunk 🍕  Makan Pizza Sepotong-sepotong

**Analogi:** Pizza 1 loyang besar. Gak mungkin dimasukin ke mulut langsung. Dipotong-potong dulu, makan sepotong-sepotong.

**Fungsinya:** Kalau ada 1000 admin, jangan ambil 1000 data sekaligus — nanti memory habis. Ambil 100 per 100 (chunk), proses, lanjut lagi.

**Implementasi:**
```php
User::role('admin')->chunk(100, function ($admins) {
    foreach ($admins as $admin) {
        // Kirim notif ke tiap admin
    }
    // Lanjut ke 100 admin berikutnya
});
```

---

## Database & Normalisasi 3NF

> **Normalisasi database** = proses merapikan database biar gak boros, gak redundant, dan konsisten. Ibaratnya: di lemari baju, jangan campur kaos kaki dengan jaket, dan jangan punya 2 lembar baju yang sama persis.

### Ringkasan 3 Tingkat Normalisasi

| Tingkat | Aturannya | Ibaratnya |
|---|---|---|
| **1NF** | Setiap kolom isinya 1 nilai (bukan array/JSON). Gak ada grup berulang (kolom1, kolom2, kolom3 yang fungsinya sama) | "Kolom `hobi` jangan diisi 'membaca,memasak,olahraga'. Bikin tabel hobi terpisah." |
| **2NF** | Kalau 1NF udah oke, pastikan setiap kolom tergantung SAMA primary key-nya | "Kalau primary key-nya `id_pesanan`, jangan ada kolom `nama_user` karena itu tergantung `id_user`, bukan `id_pesanan`." |
| **3NF** | Kalau 2NF udah oke, pastikan gak ada kolom yang tergantung sama kolom non-key lainnya | "`subtotal` tergantung `jumlah` × `harga`. Hitung aja pas dipake, jangan disimpan." |

### Penilaian Tiap Tabel (✅ = OK, ⚠️ = Kurang, ❌ = Melanggar)

| Nama Tabel | 1NF | 2NF | 3NF | Penjelasan |
|---|---|---|---|---|
| **users** | ✅ | ✅ | ✅ | **Sudah benar.** Data user rapi, id primary key, gak ada masalah |
| **keranjangs** | ✅ | ✅ | ✅ | **Sudah benar.** Keranjang belanja standar |
| **detail_keranjangs** | ✅ | ✅ | ❌ | **Masalah:** Kolom `subtotal` dihitung dari `jumlah × harga_satuan`. Seharusnya dihitung saja pas tampil, jangan disimpan |
| **pesanans** | ✅ | ✅ | ⚠️ | **Masalah:** `total_harga` dihitung dari jumlah semua detail. Ini sengaja disimpan biar query laporan cepet (denormalisasi), tapi secara aturan 3NF melanggar |
| **detail_pesanans** | ✅ | ✅ | ❌ | Sama seperti detail_keranjangs: `subtotal` seharusnya gak usah disimpan |
| **form_pesanans** | ✅ | ✅ | ✅ | **Sudah benar.** Data form pesanan |
| **pembayarans** | ✅ | ✅ | ✅ | **Sudah benar.** Data pembayaran |
| **notifikasis** | ✅ | ✅ | ✅ | **Sudah benar.** Data notifikasi |
| **galeris** | ✅ | ✅ | ✅ | **Sudah benar.** Data galeri |
| **layanans** | ⚠️ | ✅ | ✅ | **Masalah ringan:** Kolom `fitur` pakai JSON. Secara 1NF JSON itu sifatnya multi-value (melanggar 1NF), tapi di MySQL modern sudah bisa di-index jadi masih oke |
| **profil_perusahaans** | ❌ | ✅ | ⚠️ | **❌ INI YANG PALING BERMASALAH.** Tabel ini punya ~100 kolom! Banyak grup berulang: `layanan_1_nama`, `layanan_2_nama`, `layanan_3_nama`, `layanan_4_nama` — ini sebenernya data layanan yang udah ada di tabel `layanans`. Juga ada JSON `testimonis_json` yang menduplikasi tabel `testimonis` |
| **permissions, roles, pivot** | ✅ | ✅ | ✅ | **Sudah benar.** Buatan Spatie, udah standar |

### ⚠️ 3 Masalah Utama yang Ditemukan

#### ❌ Masalah 1: Tabel `profil_perusahaans` — "God Table" (Tabel Dewa)
Tabel ini punya 100+ kolom: pengaturan beranda, login, about, katalog, galeri, layanan, SEO, medsos, dsb. Semua dicampur jadi satu.

**Ibaratnya:** Lemari yang isinya campur aduk: baju, buku, piring, makanan, alat mandi semuanya di satu lemari. Ribet nyari barangnya.

**Sebaiknya:** Dipecah jadi beberapa tabel:
- `home_page_cms` → konten beranda
- `about_page_cms` → halaman tentang kami  
- `social_media_links` → link medsos
- `seo_settings` → pengaturan SEO

#### ❌ Masalah 2: Kolom `subtotal` Disimpan di Database
`detail_keranjangs.subtotal` dan `detail_pesanans.subtotal` dihitung dari `jumlah × harga_satuan`. Kenapa disimpan?

**Ibaratnya:** Kamu beli 3 apel @Rp5000. Kamu simpen di catatan "total: Rp15.000". Padahal kamu tinggal hitung 3 × 5000 pas lagi mau liat catatan.

**Sebaiknya:** Hapus kolom `subtotal`, hitung pas ditampilkan:
```php
// Di Model
public function getSubtotalAttribute() {
    return $this->jumlah * $this->harga_satuan;
}
```

#### ⚠️ Masalah 3: Kolom JSON & Repeating Groups
- `layanans.fitur` — JSON (boleh ditoleransi karena MySQL support JSON index)
- `profil_perusahaans.testimonis_json` — duplikasi data dari tabel `testimonis`
- `profil_perusahaans.layanan_1_nama` sampai `layanan_4_gambar` — duplikasi data dari tabel `layanans`

### ✅ Kesimpulan
- **Tabel bisnis utama** (keranjang, pesanan, pembayaran, notifikasi, galeri) → **sudah baik**, mayoritas 3NF
- **Tabel CMS** (profil_perusahaans) → **perlu di-refactor**, terlalu gemuk
- **Pelanggaran 3NF** di `subtotal` & `total_harga` → nggak kritis, cuma best practice yang dilanggar demi kemudahan
- **Secara fungsi aplikasi tetap jalan & aman**, cuma kalau mau lebih sempurna, tabel CMS perlu dipecah

---

## Keamanan Sistem

> **Benteng sistem:** Biar data user & aplikasi aman dari orang iseng, hacker, atau kesalahan sendiri. Berikut lapisan-lapisannya:

### 🔐 Lapisan 1: Pintu Masuk (Autentikasi)

| Lapisan | Cara Kerja | Ibaratnya |
|---|---|---|
| **Login/Register** | Laravel Breeze scaffold — sistem login standar | Resepsionis yang minta KTP |
| **API Token (Sanctum)** | Pas login via API, dikasih token (kunci). Token dipasang di setiap request | Karcis masuk konser — pake karcis setiap mau masuk |
| **Verifikasi Email** | Setelah daftar, harus verifikasi email dulu | "Klik link di email kamu buat aktifin akun" |
| **Lupa Password** | Kirim link reset ke email, kadaluarsa 60 menit | "Minta PIN baru via email" |

### 🛡️ Lapisan 2: Izin Akses (Otorisasi)

| Lapisan | Cara Kerja | Ibaratnya |
|---|---|---|
| **Role & Permission** | Ada 2 role: `admin` (bisa apa aja) dan `user` (terbatas). 12 permission spesifik | Karyawan vs Manajer. Karyawan gak bisa buka brankas |
| **Policy** | Aturan spesifik: "kamu cuma bisa lihat keranjangmu sendiri" | "Kamu cuma bisa buka lokermu sendiri, bukan loker orang lain" |
| **Middleware Role** | Pengecekan di pintu masuk halaman tertentu | "Maaf, halaman ini khusus admin" |
| **Filament Guard** | Panel admin cuma bisa diakses yang role-nya admin | "Area terlarang: khusus staf" |

### 🔒 Lapisan 3: Proteksi Data

| Lapisan | Cara Kerja | Ibaratnya |
|---|---|---|
| **Password diacak (bcrypt 12x)** | Password diacak pake algoritma bcrypt, diulang 12 kali | Bukan cuma ditutup kain, tapi dikunci 12 gembok |
| **CSRF Protection** | Token khusus di setiap form — mencegah serangan dari website lain | "Stempel rahasia" — kalau gak ada stempel, form ditolak |
| **XSS Protection** | Blade otomatis nge-escape output `{{ $isi }}` — kode JavaScript berbahaya gak jalan | Karantina: semua input diperiksa, kode bahaya dinetralin |
| **Mass Assignment** | Cuma field yang ada di `$fillable` yang bisa diisi | "Hanya kolom yang sudah ditentukan yang bisa diisi, sisanya diabaikan" |
| **Hidden Fields** | Password & token gak muncul di JSON | Data sensitif ditutup rapat |
| **SQL Injection** | Eloquent otomatis pakai parameter binding | "Pertanyaan dia udah dipastikan aman sebelum dikirim ke database" |

### 🚦 Lapisan 4: Pembatasan (Rate Limiting)

| Lapisan | Cara Kerja |
|---|---|
| **Web** | Maks 60 request per 5 menit (≈ 1 request tiap 5 detik) |
| **API** | Maks 60 request per 1 menit (≈ 1 request per detik) |
| **Verifikasi Email** | Maks 6 request per menit |
| **Reset Password** | Maks 1 request per 60 detik |

**Ibaratnya:** ATM — kalau salah PIN 3 kali, kartu diblokir. Ini mencegah brute force (nebak password berkali-kali).

### 🔄 Lapisan 5: Cegah Kekacauan Data (Concurrency)

| Lapisan | Cara Kerja | Ibaratnya |
|---|---|---|
| **Pessimistic Locking** | Pas checkout, data dikunci. User lain harus nunggu | Toilet pesawat: 1 pintu, kalau ada yang di dalem, yang lain nunggu |
| **Database Transaction** | 1 proses checkout = beberapa langkah. Kalau ada yang gagal di tengah, semua dibatalkan | "All or nothing" — kayak transfer bank: kalau gagal di tengah, uang gak jadi kepindah |

### 📁 Lapisan 6: Upload File

| Lapisan | Cara Kerja |
|---|---|
| **Cek tipe file** | Cuma gambar (jpeg, png, jpg, gif) yang diterima |
| **Cek ukuran** | Maksimal 5MB |
| **Penyimpanan** | File disimpan di folder khusus, lewat symlink (bukan langsung di public) |

**Ibaratnya:** Pos security di pintu masuk. Paket diperiksa: apa isinya? (tipe file), beratnya berapa? (ukuran). Kalau mencurigakan, ditolak.

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

> ⚠️ Jangan langsung push ke `main`. Kerja di branch masing-masing.

### 3. Install Dependency PHP

```bash
composer install
```

### 4. Konfigurasi Environment

```bash
cp .env.example .env
```

Edit file `.env`, sesuaikan database:

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

Akses: `http://localhost:8000`

### Vite (Hot Reload)

Di terminal terpisah:

```bash
npm run dev
```

### Mode Development Lengkap

```bash
composer dev
```

Menjalankan 4 hal sekaligus:
- Web server
- Queue worker (background job)
- Log viewer
- Vite HMR

### Filament Admin Panel

```
http://localhost:8000/admin
```

---

## 📋 REVIEW SISTEM — Temuan & Rekomendasi dari IT Analys

> Bagian ini berisi hasil **audit menyeluruh** terhadap sistem yang sudah dibangun. Saya menemukan beberapa masalah yang perlu diperbaiki. Setiap temuan saya beri **tingkat keparahan** (🔴 Critical / 🟠 High / 🟡 Medium / 🟢 Low) biar kalian tahu prioritasnya.

---

### 🔴 CRITICAL — Harus Diperbaiki Sekarang!

#### 1. Merge Conflict Belum Diresolve — `RoleMiddleware.php:11-31`
**Masalah:** File middleware masih ada tanda `<<<<<<< HEAD` dan `>>>>>>>`. Kode ini **gak bakal bisa jalan** karena PHP bingung milih versi yang mana.
**Ibaratnya:** Kamu terima 2 surat lamaran, terus dua-duanya kamu taruh di meja tanpa dibaca — gak jelas yang mana yang diterima.
**Perbaikan:** Buka file `app/Http/Middleware/RoleMiddleware.php`, hapus semua `<<<<<<<`, `=======`, `>>>>>>>`, dan rapihkan kodenya.

#### 2. Import Trait Dobel — `User.php:19-20`
**Masalah:** Baris 19 dan 20 sama-sama meng-import `Notifiable` dan `HasRoles`. PHP bakal error karena nganggap trait dipake 2 kali.
**Perbaikan:** Hapus salah satu baris `use` yang dobel.

#### 3. `ApiResponse` Trait Dibuat Tapi Gak Dipakai
**Masalah:** File `app/Traits/ApiResponse.php` berisi method `success()`, `error()`, `paginated()` — sangat berguna. Tapi semua API Controller malah bikin response JSON manual dengan `response()->json([...])`. Hasilnya format response API **gak konsisten** — ada yang field `status` di awal, ada yang di akhir.
**Ibaratnya:** Udah beli cetakan kue, tapi bikin kue tetap pakai tangan — hasilnya bentuknya beda-beda.
**Perbaikan:** Tambahin `use App\Traits\ApiResponse;` di semua API Controller, ganti semua `response()->json(...)` pake `$this->success(...)`.

#### 4. Ada 2 Service Keranjang yang Sama — `CartService.php` & `KeranjangService.php`
**Masalah:** `CartService.php` (38 baris) gak dipanggil di mana pun. `KeranjangService.php` (186 baris) yang dipakai. File satunya cuma numpuk.
**Ibaratnya:** Punya 2 kulkas, satunya isinya kosong gak dipake.
**Perbaikan:** Hapus `CartService.php` dan model `Cart` (kalau ada).

#### 5. Route API Nyampur di `web.php` — Baris 102-144
**Masalah:** `routes/web.php` (yang seharusnya buat halaman web) malah berisi route API dengan prefix `/api` dan middleware `throttle:60,1`. Bentrok dengan `routes/api.php`. Developer jadi bingung — harus nambah route di file yang mana?
**Perbaikan:** Hapus semua route API dari `web.php` (baris 102-144). Semua route API cukuplah di `routes/api.php`.

---

### 🟠 HIGH — Perlu Segera Diperbaiki

#### 6. Exception Message Bocor ke User — Banyak File
**Masalah:** Di `PesananService.php`, `PembayaranService.php`, dan API Controller, error message dikirim langsung ke user: *"Transisi tidak diizinkan dari..."*, *"Keranjang kosong..."*. Ini memberitahu struktur internal aplikasi ke user — **celah keamanan** (OWASP: Information Leakage).
**Ibaratnya:** Satpam ngomong ke tamu: "Maaf Pak, kunci brankas di laci ketiga kiri bawah lagi error." — Tamu jadi tahu letak brankas!
**Perbaikan:** 
- Buat Exception khusus: `OrderException`, `PaymentException`
- Kirim pesan generik ke user: `"Maaf, terjadi kesalahan. Silakan coba lagi."`
- Detail errornya simpan di log (biar developer yang lihat)

#### 7. File Upload Tersimpan Sebelum Database Dicommit — `PembayaranService.php:53-63`
**Masalah:** File bukti transfer disimpan ke disk dulu, baru data pembayaran disimpan ke database. Kalau penyimpanan DB gagal (commit error), file tetap nyangkut di disk — gak ada yang punya, jadi sampah.
**Ibaratnya:** Kamu bayar barang, uang udah dipegang kasir, tapi transaksi dibatalkan. Uang kamu gak dikembalikan.
**Perbaikan:** Simpan file ke disk **setelah** `DB::commit()` sukses. Atau di `catch` block, hapus file yang udah tersimpan.

#### 8. Ekstensi File Dari Client — Rawan Dispoof — `PembayaranService.php:55`
**Masalah:** `$file->extension()` pakai ekstensi yang dikirim client. Bisa dimanipulasi: file berbahaya `virus.php` dikasih ekstensi `.jpg`, lolos masuk.
**Perbaikan:** Pakai `$file->hashName()` (Laravel generate nama unik otomatis), atau validasi MIME type beneran (baca isi file, bukan lihat ekstensi).

#### 9. Chart Revenue Pake 12 Query — `RevenueChartWidget.php:28-29`
**Masalah:** Untuk nampilin grafik 12 bulan, kodenya nge-loop 12 kali dan tiap loop jalanin 1 query SUM ke database. Padahal **1 query aja cukup** pake `GROUP BY`.
**Ibaratnya:** Mau hitung total belanja 12 bulan. Daripada liat 1 lembar rekapan, kamu malah buka dompet dan ngitung koin satu-satu tiap bulan.
**Perbaikan:** 
```php
$revenue = Pesanan::selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, SUM(total_harga) as total")
    ->where('created_at', '>=', Carbon::now()->subMonths(11)->startOfMonth())
    ->groupBy('month')
    ->pluck('total', 'month');
```

#### 10. Ada 2 Sistem Checkout Berbeda — Web Controller vs Service
**Masalah:** 
- **Web** `PesananController::checkout()` — nulis query langsung, pake kolom `id_paket`, `jumlah`, `catatan_custom`
- **API + Service** `PesananService::checkout()` — pake `lockForUpdate()`, kolom `id_layanan`, `quantity`, `custom_data`
- Kolomnya beda, logikanya beda, validasinya beda!
**Ibaratnya:** Di toko offline kamu bayar pakai uang cash, di toko online bayar pakai QRIS — tapi harganya beda karena sistemnya beda sendiri. Gak masuk akal!
**Perbaikan:** Web controller harusnya panggil `PesananService::checkout()` juga. Hapus coding checkout manual di `PesananController::checkout()`.

#### 11. Ada 3 Jalur Notifikasi yang Berbeda — Bikin Duplikasi
**Masalah:**
1. Model `Pesanan` punya event `booted()` yang bikin record `Notifikasi` langsung
2. Service `PesananService::emitStatusChangeEvent()` dispatch Event → diproses Listener
3. Ada Listener `SendOrderConfirmationEmail`, `NotifyPaymentRequired`, dll.
**Akibat:** 1 perubahan status bisa trigger notifikasi 2-3 kali. User dikasih notif dobel!
**Ibaratnya:** Mau ngasih tahu "makan siang siap", kamu teriak, chat WhatsApp, dan telepon — 3 kali, bikin bingung.
**Perbaikan:** Matikan event `booted()` di model `Pesanan.php` (baris 119-172). Serahkan semua notifikasi ke Event + Listener saja.

#### 12. `PembayaranService` Melewati `PesananService` — State Machine Dilewati!
**Masalah:** Di `PembayaranService::verifyPayment()` (baris 98-99), status pesanan diubah langsung pake `$pesanan->update()` tanpa lewat `PesananService::updateStatus()`. Akibatnya, **validasi state machine dilewati** — status bisa lompat sembarangan.
**Ibaratnya:** Mau ke lantai 3 naik lift, tapi kamu naik tangga darurat dan buka paksa pintu lantai 3 dari belakang — aturan lift dilanggar!
**Perbaikan:** Inject `PesananService` ke `PembayananService`, panggil `$this->pesananService->updateStatus(...)`.

#### 13. Foto Fallback Pake Link Unsplash — Rawan Broken Image
**Masalah:** Di beberapa file Blade (`keranjang/index.blade.php:80-83`, `pesanan/index.blade.php:72`, dll) ada hardcoded URL gambar Unsplash. Kalau internet mati atau URL berubah, gambar rusak.
**Perbaikan:** Simpen gambar fallback lokal di folder `public/images/`.

#### 14. Nomor Rekening Hardcoded di View — `pesanan/show.blade.php:59,247`
**Masalah:** Nomor rekening `123-456-7890` ditulis langsung di file Blade dan di script `copyToClipboard('1234567890')`. Kalau ganti rekening, harus edit file. Juga, ini data sensitif bocor ke kode.
**Perbaikan:** Ambil dari database (tambah kolom `no_rekening` di `profil_perusahaans`) atau dari `.env`.

---

### 🟡 MEDIUM — Perlu Dirapihkan

#### 15. Nama Kolom Gak Konsisten — `DetailKeranjang` vs `DetailPesanan`
| DetailKeranjang | DetailPesanan |
|---|---|
| `id_paket` | `id_layanan` |
| `jumlah` | `quantity` |
| `catatan_custom` | `custom_data` |
| `subtotal` | `subtotal` (sama, untung) |

Padahal dua tabel ini nyaris identik secara fungsi. Nama kolom beda bikin bingung pas mantau kode.
**Perbaikan:** Standarisasi nama kolom, misal: `id_layanan`, `quantity`, `custom_data`.

#### 16. Angka Ajaib `3` (Max Item Keranjang) Tersebar
**Masalah:** Batas maksimal 3 item di keranjang ditulis langsung di beberapa tempat: `KeranjangService.php:47,175`, `KeranjangController.php:53`. Kalau mau ganti jadi 5, harus edit banyak file.
**Perbaikan:** Simpan di `config/app-settings.php`: `'cart_max_items' => 3`, terus panggil `config('app-settings.cart_max_items')`.

#### 17. Gak Pake Pagination di Daftar Pesanan Web — `PesananController.php:53`
**Masalah:** `$pesanans = $query->get();` — ambil semua data tanpa batas. User dengan 1000 pesanan bakal loading berat.
**Perbaikan:** Ganti jadi `$query->paginate(10);`.

#### 18. Kode Pesanan Cuma 6 Random Karakter — Bisa Tabrakan
**Masalah:** `ORD-20260603-XXXXXX` — 6 karakter random. Kalau ada 1000 order di hari yang sama, kemungkinan tabrakan kecil tapi ada.
**Perbaikan:** Pakai `Str::uuid()` atau `Illuminate\Support\Str::orderedUuid()`.

#### 19. CSRF Token di Inline Script — `keranjang/index.blade.php:303`
**Masalah:** `'{{ csrf_token() }}'` ditulis langsung di dalem tag `<script>`. Kalau script ini di-cache oleh service worker, token bocor.
**Perbaikan:** Ambil dari `<meta name="csrf-token">` via JavaScript: `document.querySelector('meta[name="csrf-token"]').content`.

#### 20. `resources/js/api.js` — File Dibuat Tapi Gak Dipanggil
**Masalah:** File `api.js` isinya bagus (konfigurasi Axios, interceptor, dll) tapi gak di-import di `app.js` atau `bootstrap.js`. Jadinya file ini gak pernah dipakai.
**Perbaikan:** Import di `app.js`: `import './api';`.

#### 21. Kode JavaScript Nempel di Blade — Bikin Susah Dirawat
**Masalah:** Ada 100+ baris JavaScript (toast notification, changeQty, copyToClipboard, previewFile) nulis langsung di file `.blade.php`. Kalau butuh fungsi yang sama di halaman lain, harus copy-paste lagi.
**Ibaratnya:** Resep masakan ditulis di piring. Mau masak lagi, harus liat piring kotor bekas kemaren.
**Perbaikan:** Pindahin ke file `.js` terpisah di `resources/js/`, panggil via `<script>` atau di import.

#### 22. Toast Notifikasi Dobelan — 2 Implementasi Sama
**Masalah:** `keranjang/index.blade.php:372-394` dan `pesanan/show.blade.php:278-335` — kode toast notifikasinya hampir identik (copy-paste).
**Perbaikan:** Buat 1 file `resources/js/components/toast.js` yang dipakai bersama.

#### 23. Aksesibilitas — Gambar Gak Pake `alt`
**Masalah:** Beberapa tag `<img>` di `keranjang/index.blade.php` dan `pesanan/index.blade.php` gak punya atribut `alt`. Ini penting buat:
- Pembaca layar (tunanetra)
- SEO
- Kalau gambar gagal load, masih ada teks deskripsi

#### 24. Campur Bahasa Inggris-Indonesia di View
**Masalah:** `pesanan/show.blade.php` pakai Inggris ("Payment Verification", "Bank Name", "Confirm Payment"), sementara halaman lain pakai Indonesia ("Keranjang Belanja", "Riwayat Pesanan"). Gak konsisten.
**Perbaikan:** Pilih salah satu, konsisten untuk semua halaman.

#### 25. Loading State Gak Ada — `keranjang/index.blade.php:305`
**Masalah:** Tombol quantity (+/-) gak di-disable selama request berlangsung. User bisa klik cepat 10 kali, dikirim 10 request.
**Perbaikan:** Disabled tombol selama request, kasih spinner/loading.

#### 26. `axios` Gak Dapet CSRF Token dari Meta Tag
**Masalah:** `resources/js/bootstrap.js` cuma set `X-Requested-With`, tapi gak ambil CSRF token dari `<meta name="csrf-token">`.
**Akibat:** Request POST/PUT/DELETE via Axios bisa kena error 419 (CSRF mismatch).
**Perbaikan:** Tambah:
```js
let token = document.querySelector('meta[name="csrf-token"]');
if (token) window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
```

---

### 🟢 LOW — Catatan Kecil

| No | Temuan | Lokasi | Saran |
|---|---|---|---|
| 27 | Import `HasCompanyCms` gak dipakai | `ProfilPerusahaan.php:8` | Hapus import yang gak dipake |
| 28 | Import `DB` gak dipakai | `RevenueChartWidget.php:8` | Hapus |
| 29 | Import `Str`, `DetailPesanan`, dll gak dipakai | `PesananController.php:5-16` | Hapus import yang gak dipake |
| 30 | Nullsafe `?->` di kolom yang gak mungkin null | `PesananResource.php:21` | Ganti `$this->tanggal_pesan?->toIso8601String()` jadi `$this->tanggal_pesan->toIso8601String()` |
| 31 | `EventServiceProvider::shouldDiscoverEvents()` = false | `EventServiceProvider.php:63` | Ganti ke `true` biar auto-detect |

---

### 🏗️ PRODUCTION READINESS — Persiapan Go-Live

#### 🔴 Critical Sebelum Go-Live

| No | Setting | Lokasi | Sekarang | Seharusnya |
|---|---|---|---|---|
| 1 | `APP_DEBUG` | `.env` | `true` | **`false`** — biar error gak munculin isi kode ke user |
| 2 | `LOG_LEVEL` | `.env` | `debug` | **`warning`** — production gak perlu log debug (bikin hardisk penuh) |
| 3 | HTTPS Redirect | `.htaccess` | Tidak ada | Tambahin redirect HTTP → HTTPS |
| 4 | `SESSION_SECURE_COOKIE` | `config/session.php` | `false` | **`true`** — cookie cuma dikirim lewat HTTPS |
| 5 | `CACHE_STORE` | `.env` | `database` | **`redis`** — cache lewat database = percuma, tetap lambat |
| 6 | `QUEUE_CONNECTION` | `.env` | `database` | **`redis`** — queue database polling tiap detik, boros resource |
| 7 | `SESSION_DRIVER` | `.env` | `database` | **`redis`** — session database gak scalable |
| 8 | `SANCTUM_STATEFUL_DOMAINS` | `config/sanctum.php` | `localhost` | Tambah domain production |

#### Rekomendasi Infrastructure Production

```
           ┌─────────────┐
           │   User/App   │
           └──────┬──────┘
                  │ HTTPS
           ┌──────▼──────┐
           │   Nginx/CDN  │  → Static assets (CSS/JS/Images)
           └──────┬──────┘
                  │
           ┌──────▼──────┐
           │   PHP-FPM    │  → Laravel App (multiple workers)
           │   + Octane   │  → 10-50x lebih cepat dari PHP-FPM biasa
           └──────┬──────┘
                  │
     ┌────────────┼────────────┐
     │            │            │
┌────▼─────┐ ┌───▼────┐ ┌───▼──────┐
│  Redis    │ │ MySQL  │ │  S3      │
│(Cache +   │ │ (Read  │ │(File     │
│ Session + │ │  Write  │ │ Upload)  │
│ Queue)    │ │ Replica)│ │          │
└──────────┘ └────────┘ └──────────┘
```

**Penjelasan:**
- **Nginx** — reverse proxy, serve static files, handle HTTPS, load balancing
- **PHP-FPM** — jalanin Laravel, bisa 10-50 worker
- **Redis** — cache, session, queue. Lebih cepat daripada MySQL karena data di RAM
- **MySQL** — database utama. Production harus pake read replica
- **S3** — penyimpanan file (bukti transfer, galeri) yang bisa diakses banyak server
- **Laravel Octane** — meningkatkan throughput 10-50x dibanding PHP-FPM biasa

#### Optimasi Vite untuk Production

Di `vite.config.js`:

```js
export default defineConfig({
    plugins: [laravel({ input: [...], refresh: false })],  // refresh: false untuk production
    build: {
        chunkSizeWarningLimit: 500,
        rollupOptions: {
            output: {
                manualChunks: {
                    vendor: ['alpinejs', 'axios'],  // Pisah vendor biar cache-nya awet
                },
            },
        },
    },
})
```

---

### 📊 KESIMPULAN AKHIR

| Aspek | Status | Rekomendasi |
|---|---|---|
| **Fungsionalitas** | ✅ Berjalan baik | Fitur inti (auth, keranjang, pesanan, bayar, notif, galeri) lengkap & berfungsi |
| **Arsitektur** | ⚠️ Perlu perbaikan | Service layer bagus, tapi ada dual checkout system & dual notification → perlu disatukan |
| **Database** | ⚠️ Sebagian 3NF | Tabel transaksional ✅, tabel CMS ❌ (god table 100+ kolom). Derived columns perlu dihapus |
| **Keamanan** | ✅ Cukup baik | Ada 25+ lapisan keamanan. Yang perlu diperbaiki: exception message leakage, file commit order, CSRF di Axios |
| **Clean Code** | ⚠️ Perlu dirapikan | Merge conflict ❌, dead code, import dobel, nama kolom inkonsisten, JS nempel di Blade |
| **Scalability** | ⚠️ Perlu Redis | Sekarang pake database untuk cache/session/queue → ganti ke Redis. Tambah Laravel Octane untuk throughput tinggi |
| **Production Readiness** | ⚠️ Belum siap | `.env` masih debug, belum HTTPS, belum ada Redis, CORS belum diatur |

#### Total Temuan: 31+ Issue
| 🔴 Critical | 🟠 High | 🟡 Medium | 🟢 Low |
|---|---|---|---|
| 5 | 9 | 12 | 5 |

---

## Tim Pengembang

| Nama | GitHub |
|---|---|
| **lanaaadev10-cmd** | https://github.com/lanaaadev10-cmd |
| **Hillmi-Nazwar** | https://github.com/Hillmi-Nazwar |
| **ahmadsepta2405** | https://github.com/ahmadsepta2405 |
| **zenverovenopasa** | https://github.com/zenverovenopasa |

---

## Lisensi

Proyek ini dikembangkan untuk **Project Based Learning (PBL)**.


