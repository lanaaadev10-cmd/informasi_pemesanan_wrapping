# 📋 PANDUAN ADMIN PANEL - KONFIGURASI WEBSITE

Selamat datang di Admin Panel! Di sini Anda dapat mengatur semua konten dan konfigurasi website **tanpa perlu edit code**.

---

## 🎯 STRUKTUR ADMIN PANEL

Admin Panel dibagi menjadi **5 kategori utama**. Berikut adalah panduan lengkap:

### **1️⃣ SETUP DASAR** 
Bagian pertama untuk konfigurasi dasar website Anda.

#### 📌 Tab: Informasi Utama
- **Nama Perusahaan**: Nama bisnis Anda (muncul di logo area)
- **Deskripsi/Tagline**: Tagline singkat bisnis Anda
- **Email & Telepon**: Kontak utama (untuk hubungi kami)
- **Alamat**: Lokasi fisik
- **Google Maps**: URL embed peta lokasi

#### 🎨 Tab: Branding & SEO
- **Logo**: Upload logo perusahaan (PNG transparan untuk hasil terbaik)
- **Meta Title**: Judul yang muncul di Google (max 70 karakter)
- **Meta Description**: Deskripsi di Google (max 160 karakter)

#### 🌈 Tab: Tema & Styling Global
Pengaturan tampilan visual global website.

| Field | Penjelasan | Contoh |
|-------|-----------|--------|
| **Warna Accent (Primary Color)** | Warna utama yang digunakan di seluruh website (buttons, highlights) | #f2994a (orange) |
| **Layout Style** | Lebar konten website | Full Width atau Compact |
| **Dark Mode** | Aktifkan/nonaktifkan tema gelap | Toggle ON/OFF |

💡 **Tips**: Ubah accent color untuk instant branding makeover di seluruh website!

---

### **2️⃣ KONTEN HALAMAN PUBLIK**
Bagian untuk mengatur konten halaman yang terlihat pengunjung.

#### 🏠 Tab: Beranda (Home Page)
Halaman pertama yang dilihat pengunjung.

**Bagian 1: Hero - Judul & Tagline**
- Badge text: Teks kecil di atas judul (misal: "Professional Car Wrapping Indonesia")
- Judul Baris 1 & 2: Judul besar (bisa di-split jadi 2 baris)
- Subjudul: Deskripsi di bawah judul

**Bagian 2: Statistik**
- Nilai 1 & Label 1: Misal "500+" dan "Supercars Wrapped"
- Nilai 2 & Label 2: Misal "5 Tahun" dan "Garansi Material"

**Bagian 3: 4 Kartu Keunggulan**
- Setiap kartu punya: Judul + Deskripsi
- Contoh: "Kualitas Material Grade-A" dengan penjelasannya

**Bagian 4: CTA & Langkah Mudah**
- Judul CTA: "Siap Mengubah Tampilan Kendaraan?"
- Subtitle CTA: Deskripsi ajakan
- 3 Langkah: Judul + Deskripsi untuk setiap step

💡 **Tips**: Edit semua ini untuk customize homepage sesuai brand Anda!

#### ℹ️ Tab: Tentang Kami (Page)
Halaman profil perusahaan.

**Bagian 1: Visi & Misi**
- Visi: Pernyataan visi perusahaan (bisa HTML)
- Misi: Pernyataan misi perusahaan (bisa HTML)

**Bagian 2: Sejarah**
- Sejarah Perjalanan: Cerita perjalanan bisnis (bisa HTML dengan formatting)

**Bagian 3: Tim**
- Judul Section: "Dibalik Setiap Detail Sempurna"
- Deskripsi: Intro tentang tim
- Data Tim: (format JSON - biar mudah, bisa copas template di bawah)

```json
[
  {
    "nama": "Adrian Wirya",
    "posisi": "Lead Technician",
    "foto": "path/ke/foto.jpg"
  },
  {
    "nama": "Pratama",
    "posisi": "Head Designer",
    "foto": "path/ke/foto2.jpg"
  }
]
```

#### 💼 Tab: Layanan (Page)
Halaman layanan/paket yang ditawarkan.

**Bagian 1: Hero**
- Judul: "Precision in Every Layer"
- Deskripsi: Penjelasan layanan

**Bagian 2: 4 Layanan**
Untuk setiap layanan:
- Nama: Nama layanan (misal "Stealth Matte")
- Harga: Harga (misal "Rp 12.500.000")
- Deskripsi: Deskripsi layanan
- Fitur: List fitur (format JSON)
  ```json
  ["Proteksi UV 10 Tahun", "Garansi 5 Tahun", "Proses 3 Hari"]
  ```
- Gambar: Upload foto layanan

**Bagian 3: Garansi & CTA**
- Judul Garansi: "Garansi Resmi"
- Deskripsi Garansi: Penjelasan garansi
- Judul CTA: "Siap Mengubah?"
- Deskripsi CTA: Ajakan bertindak

#### 📸 Tab: Katalog (Page)
Halaman daftar produk/wrapping.

- Hero Title: Judul halaman
- Hero Description: Deskripsi halaman
- Intro Text: Teks pengenalan produk
- 4 Feature Highlights: Judul + deskripsi untuk 4 fitur utama
- Empty State: Pesan ketika tidak ada produk

💡 **Tip**: Katalog akan auto-populate dari data layanan yang Anda buat!

#### 🎭 Tab: Galeri (Page)
Halaman portfolio/galeri karya.

- Hero Title: "Precision Mastery Gallery"
- Hero Description: Deskripsi galeri
- Intro Text: Pengenalan portfolio
- Empty State: Pesan ketika galeri kosong

💡 **Tip**: Galeri akan menampilkan foto-foto yang diupload users!

#### 👔 Tab: Profil (Page)
Halaman tentang profil detail perusahaan.

- **3 Pillar Highlights**: Judul + deskripsi untuk 3 pilar utama bisnis
  - Pillar 1: Misal "Precision Engineering"
  - Pillar 2: Misal "Bespoke Materials"
  - Pillar 3: Misal "White-Glove Service"

---

### **3️⃣ KONFIGURASI LAYOUT**
Pengaturan layout & tampilan halaman.

#### ⚙️ Tab: Konfigurasi Tentang Kami
Layout settings untuk halaman Tentang Kami.

| Konfigurasi | Pilihan | Penjelasan |
|-------------|---------|-----------|
| **Jumlah Kolom Nilai** | 3 atau 4 | Berapa kolom untuk section nilai/komitmen |
| **Tampilkan Section Nilai** | Toggle | Tampilkan atau sembunyikan section nilai |
| **Tampilkan Section Sejarah** | Toggle | Tampilkan atau sembunyikan sejarah |
| **Tampilkan Section Tim** | Toggle | Tampilkan atau sembunyikan tim |

#### ⚙️ Tab: Konfigurasi Layanan
Layout settings untuk halaman Layanan.

| Konfigurasi | Pilihan | Penjelasan |
|-------------|---------|-----------|
| **Grid Columns** | 3 atau 4 | Jumlah kolom untuk service cards |
| **Card Style** | Standard/Compact/Large | Ukuran/style kartu |
| **Tampilkan "Mengapa Memilih Kami?"** | Toggle | Tampilkan section manfaat |
| **Tampilkan Section Garansi** | Toggle | Tampilkan section garansi |

💡 **Tips**: Coba toggle sections untuk lihat mana yang cocok dengan design Anda!

---

### **4️⃣ DASHBOARD CUSTOMER**
Pengaturan halaman dashboard untuk member/customer yang login.

#### 📊 Tab: Dashboard Configuration
Konfigurasi greeting dan section di dashboard.

- Section Titles: Judul untuk setiap section
  - Quick Services Title: Judul layanan cepat
  - Activity Title: Judul aktivitas terbaru

#### 📋 Tab: Pesanan & Keranjang
Konten halaman pesanan dan keranjang.

**Pesanan Page:**
- Page Title: Judul halaman pesanan
- Page Description: Deskripsi halaman
- Filter Labels: Label tombol filter (Semua, Berjalan, Selesai)
- Empty State: Pesan ketika tidak ada pesanan

**Keranjang Page:**
- Hero Text: "YOUR SELECTION"
- Page Title: "Keranjang Belanja"
- Page Subtitle: Deskripsi keranjang
- Warranty Title & Description: Section garansi
- Service Charge: Label & amount biaya layanan

#### 💳 Tab: Checkout Page
Konfigurasi halaman checkout.

**Step Labels:**
- Setiap step punya nama (Pilih Layanan, Data Kendaraan, dll)

**Step Titles:**
- Judul untuk step tertentu (misal "Data Kendaraan & Jadwal")

**Warranty Badges:**
- Badge 1: "Garansi 2 Tahun"
- Badge 2: "UV Protection"

---

### **5️⃣ PENGATURAN LAINNYA**

#### 🔗 Tab: Sosial Media
Link ke profile sosial media.

- Instagram, Facebook, TikTok, WhatsApp
- Masukkan URL lengkap profile Anda

---

## 🎓 QUICK START GUIDE

### Hari Pertama:
1. Isi **Informasi Utama** (nama, email, telepon, alamat)
2. Upload **Logo** di Branding & SEO
3. Pilih **Warna Accent** di Tema & Styling Global
4. Setup **Beranda** dengan hero title & subtitle

### Minggu Pertama:
5. Isi **Tentang Kami** (visi, misi, sejarah)
6. Setup **4 Layanan** dengan nama, harga, deskripsi
7. Upload **Tim** dengan foto
8. Atur **Sosial Media** links

### Maintenance:
- Update konten secara berkala
- Ganti banner/foto jika perlu
- Tweak konfigurasi layout sesuai feedback

---

## 💡 TIPS & TRICKS

### Rich Text Editor
Beberapa field punya text editor dengan formatting:
- **B** untuk Bold
- *I* untuk Italic
- Lists untuk bullet points
- H2/H3 untuk sub-headers

### JSON Format (untuk list)
Beberapa field perlu format JSON array:
```json
["item 1", "item 2", "item 3"]
```
Copy-paste template di atas dan ganti items!

### Image Upload
- Ukuran rekomendasi: 1920x1080px atau lebih
- Format: JPG, PNG, WebP
- Logo: Gunakan PNG transparan untuk hasil terbaik

### Color Picker
Klik tombol warna untuk pilih warna:
- Atau input hex code langsung (misal #f2994a)
- Atau gunakan nama warna (misal "orange")

---

## ❓ FAQ

**Q: Berapa lama perubahan muncul?**
A: Instant! Perubahan langsung muncul di website.

**Q: Bisa undo perubahan?**
A: Bisa klik "Cancel" sebelum "Save changes".

**Q: Gimana kalau website jadi aneh?**
A: Klik "Lihat Website" untuk preview, atau klik tombol X di browser untuk kembali.

**Q: Field ini untuk apa sih?**
A: Hover di ? icon untuk lihat penjelasan (atau lihat guide ini!).

---

## 📞 SUPPORT

Ada pertanyaan? Lihat setiap field punya helper text yang penjelasan lebih detail!

**Happy configuring! 🚀**
