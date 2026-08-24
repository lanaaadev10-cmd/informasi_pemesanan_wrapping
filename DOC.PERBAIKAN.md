
# 📘 Dokumen Perbaikan Website — Informasi Pemesanan Wrapping

Dokumen ini mencatat semua masalah (bug) yang pernah ditemukan dan cara memperbaikinya, ditulis dengan bahasa sederhana supaya semua orang — dari pemilik usaha sampai admin — bisa mengerti apa yang terjadi dan bagaimana sistemnya bekerja.

---

## Perbaikan Awal (Hari Pertama)

### 1. File pengaturan website hilang (file `.env`)

**Bug-nya apa?** Website tidak bisa jalan sama sekali karena file pengaturan rahasia (`.env`) tidak ada.

**Kenapa bisa terjadi?** File ini berisi kunci dan pengaturan penting (termasuk koneksi ke database). Entah bagaimana, file-nya hilang.

**Gimana cara memperbaikinya?** Dibuatkan ulang file `.env`. Penting: memakai database yang lama (tanpa membuat ulang dari awal), cukup menjalankan `php artisan key:generate` untuk membuat kunci baru, lalu `php artisan optimize:clear` untuk membersihkan cache.

---

### 2. Bagian kode yang tidak terpakai bisa mengganggu website

**Bug-nya apa?** Ada potongan kode yang membuat website rawan error, padahal fungsinya tidak dipakai.

**Kenapa bisa terjadi?** Saat pengembangan, ada fitur yang ditinggalkan tapi kodenya masih menempel di dua tempat: file `bootstrap/app.php` (pengaturan tengah) dan `routes/web.php` (daftar alamat halaman) untuk menu "Pesanan Offline Admin".

**Gimana cara memperbaikinya?** Kode yang tidak dipakai itu dikomen saja (dimatikan tanpa dihapus), supaya bisa diaktifkan lagi kapan pun dibutuhkan.

---

## Perbaikan Sesi 2 (6 Juli 2026)

### 3. Pengaturan website (settings) tidak bisa dimuat

**Bug-nya apa?** Website memanggil kelas pengaturan yang ternyata tidak ada, sehingga halaman error.

**Kenapa bisa terjadi?** Di file `config/settings.php` tertulis nama kelas yang tidak pernah dibuat di project. Namanya salah/ketinggalan zaman.

**Gimana cara memperbaikinya?** Diganti memakai kelas bawaan yang sudah pasti ada dari sistem (`Spatie\LaravelSettings\...DatabaseSettingsRepository`).

---

### 4. Daftar keunggulan layanan "Custom 2" rusak

**Bug-nya apa?** Isi keunggulan layanan di database salah format, sehingga bisa muncul aneh atau error.

**Kenapa bisa terjadi?** Datanya ditulis dengan struktur yang salah (bentuk kotak berisi kotak), padahal seharusnya berupa daftar teks biasa.

**Gimana cara memperbaikinya?** Diperbaiki langsung di database: format diubah menjadi daftar teks biasa yang benar.

---

### 5. Alamat halaman Katalog salah arah

**Bug-nya apa?** Klik "Katalog" di website mengarah ke fungsi yang sudah tidak ada, hasilnya error.

**Kenapa bisa terjadi?** Alamat (route) masih menunjuk ke fungsi lama yang sudah dihapus saat perombakan sebelumnya.

**Gimana cara memperbaikinya?** Alamatnya diarahkan ke fungsi yang benar (`CustomerController::katalog`).

---

### 6. Error "memanggil data kosong" di beberapa halaman

**Bug-nya apa?** Beberapa halaman tampil error "call to member function on null" karena mengambil data yang ternyata kosong.

**Kenapa bisa terjadi?** Tampilan mengambil data dari data lain (relasi) yang kadang belum terisi — misalnya nama pelanggan saat pesanan belum punya pelanggan.

**Gimana cara memperbaikinya?** Di 6 tempat ditambahkan tanda aman (`?->`) di file `_order-card.blade.php` dan `_recent-activity.blade.php`. Kalau datanya kosong, sistem melewatinya begitu saja tanpa error.

---

### 7. Beberapa pengaturan dipakai tapi tidak didaftarkan

**Bug-nya apa?** Beberapa bagian website mengambil pengaturan yang belum terdaftar di kelasnya, jadi bisa tidak tampil atau error.

**Kenapa bisa terjadi?** Pengaturan itu dipakai di tampilan website, tapi lupa didaftarkan di file pengaturan.

**Gimana cara memperbaikinya?** Ditambahkan properti yang hilang di 3 file pengaturan:
- Dashboard customer: judul & subjudul dashboard.
- Halaman beranda: judul, gambar utama, judul & subjudul keunggulan.
- Konten umum: tombol WhatsApp & label "Temukan Kami".

---

### 8. Tombol hapus di keranjang bisa diakses orang lain

**Bug-nya apa?** Fungsi hapus barang di keranjang memakai perintah verifikasi yang tidak tersedia, jadi cek keamanannya tidak jalan.

**Kenapa bisa terjadi?** Kelas dasar controller di project ini tidak punya fitur `authorize()` (pengecekan izin bawaan).

**Gimana cara memperbaikinya?** Diganti pengecekan manual: sistem memastikan pemilik keranjang = orang yang login. Kalau beda, akses ditolak.

---

### 9. Perubahan data profil tidak langsung muncul di website

**Bug-nya apa?** Admin mengubah data Profil Perusahaan, tapi tampilan website tidak berubah sampai 24 jam.

**Kenapa bisa terjadi?** Data pengaturan disimpan sementara (cache) selama 24 jam agar website cepat. Karena itu data baru tidak langsung terlihat.

**Gimana cara memperbaikinya?** Dua cara dicoba (perintah `settings:clear-cache` dan `SettingsCacheFactory`), tapi keduanya tidak berhasil. Untuk sementara fitur "hapus cache otomatis" dicabut.

---

### 10. Menu Profil Perusahaan di admin bermasalah

**Bug-nya apa?** Menu "Profil Perusahaan" di halaman admin tidak berfungsi dengan baik dan muncul error PHP.

**Kenapa bisa terjadi?** (a) Menu ini sebenarnya tidak dibutuhkan di admin, dan (b) ada tipe data yang salah (nullable vs tidak) sehingga error.

**Gimana cara memperbaikinya?** Menu disembunyikan dari sidebar admin dengan `shouldRegisterNavigation = false`, dan tipe datanya diperbaiki ke yang benar (`bool`).

---

## Perbaikan Sesi 3 (6 Juli 2026) — Form "New Pesanan" di Admin

### 11. Form buat pesanan baru di admin tidak bisa diisi

**Bug-nya apa?** Halaman New Pesanan (Transaksi → Kelola Pesanan) semua kolomnya abu-abu dan kosong, admin tidak bisa mengisi apa pun.

**Kenapa bisa terjadi?** Kolom-kolom itu diatur terkunci (disabled) padahal harusnya bisa diisi. Selain itu ada kolom nomor telepon yang tersedia di database tapi tidak pernah dipakai di form.

**Gimana cara memperbaikinya?**
- Nomor telepon pelanggan (`whatsapp_number`) didaftarkan supaya bisa disimpan.
- Field pelanggan, tanggal pesan, dan total harga dibuka (bisa diisi/dipilih) di halaman New maupun Ubah.
- ID Pesanan dibuat otomatis oleh sistem (contoh: PSN-A3B9X7K2) dan terkunci.
- Tanggal pesan otomatis terisi tanggal hari ini, status otomatis "Menunggu Verifikasi Pesanan" saat buat baru.

---

## Perbaikan Sesi 4 (19 Juli 2026) — Perombakan Besar

### 12. Foto galeri tidak muncul

**Bug-nya apa?** Semua foto galeri tampil kosong/broken.

**Kenapa bisa terjadi?** File fotonya memang tidak pernah ada di folder penyimpanan website.

**Gimana cara memperbaikinya?** Foto galeri memakai gambar gratis dari Unsplash (tidak perlu menyimpan file di server). Ada tabel berisi pasangan "alamat lama → URL baru" dan fungsi penerjemahnya.

---

### 13. Teks website tersebar di mana-mana

**Bug-nya apa?** Semua teks (nama toko, judul, tombol) ditulis langsung di setiap halaman, jadi mengubah satu kalimat harus mengedit banyak file.

**Kenapa bisa terjadi?** Sebelumnya tidak ada satu tempat khusus untuk teks.

**Gimana cara memperbaikinya?** Dibuat satu file pusat (`app/Helpers/StaticContent.php`) untuk semua teks. Sekarang ganti teks cukup edit satu file, refresh, langsung berubah. Halaman yang sudah memakainya: navbar & footer, beranda, profil perusahaan, layanan, tentang kami, dan galeri.

---

### 14. Data galeri & layanan dipindahkan ke database

**Bug-nya apa?** Menambah/mengubah galeri atau layanan harus mengedit kode program.

**Kenapa bisa terjadi?** Datanya ditulis manual di file tampilan.

**Gimana cara memperbaikinya?**
- Galeri: disimpan di database, diisi 3 data contoh (Porsche 911 GT3, Mercedes-Benz S-Class, Lamborghini Urus). Admin bisa kelola lewat menu Galeri.
- Layanan: disimpan di database, diisi 3 layanan real (Variasi Mobil, Kaca Film, Audio Mobil — custom). Admin bisa kelola lewat menu Layanan.

---

### 15. Galeri belum tampil di dashboard customer

**Bug-nya apa?** Foto galeri sudah dikirim ke dashboard customer tapi tidak ditampilkan.

**Gimana cara memperbaikinya?** Ditambahkan bagian "Galeri Portofolio" di dashboard customer yang menampilkan foto galeri dalam bentuk grid.

---

### 16. Filter kategori galeri error 500

**Bug-nya apa?** Klik tombol filter kategori di halaman galeri menghasilkan error halaman rusak (500).

**Kenapa bisa terjadi?** Tombol memanggil fungsi yang tidak ada.

**Gimana cara memperbaikinya?** Fungsinya ditambahkan, dan daftar kategori otomatis menyesuaikan data di database.

---

### 17. Alamat API galeri error

**Bug-nya apa?** Alamat `/api/galeri/*` error karena filenya tidak ada.

**Gimana cara memperbaikinya?** File `GaleriApiController.php` dibuat, isinya mengambil data dari database.

---

### 18. Database tidak bisa dibangun ulang

**Bug-nya apa?** Perintah `php artisan migrate:fresh --seed` (membangun ulang database) gagal karena 2 kesalahan: kunci penghubung (foreign key) menunjuk ke tabel yang tidak ada, dan ada kunci unik yang dobel.

**Gimana cara memperbaikinya?** Kunci penghubung diarahkan ke tabel yang benar (`layanans`), dan kunci dobel dihapus. Sekarang pembangunan ulang database berjalan 100% mulus.

---

## Catatan Perbaikan Bug — 21 Juli 2026

### 19. Foto layanan di dashboard customer tidak muncul

**Bug-nya apa?** Semua layanan di dashboard customer hanya menampilkan ikon pengganti, fotonya kosong.

**Kenapa bisa terjadi?** Data awal (seeder) tidak mengisi kolom foto contoh, jadi semua layanan fotonya kosong.

**Gimana cara memperbaikinya?** Admin perlu meng-upload foto tiap layanan lewat panel admin agar fotonya terisi.

---

### 20. Foto katalog beda dengan foto di halaman layanan

**Bug-nya apa?** Gambar layanan di halaman Katalog tidak sama dengan di halaman Layanan, padahal layanannya sama.

**Kenapa bisa terjadi?** Kedua halaman memakai daftar gambar cadangan (fallback) yang berbeda saat foto asli kosong.

**Gimana cara memperbaikinya?** Dibuat satu daftar gambar cadangan bersama di `StaticContent.php` (`LAYANAN_FALLBACK_IMAGES`), dan kedua halaman memakainya — jadi hasilnya seragam.

---

### 21. Foto galeri broken setelah upload lewat admin

**Bug-nya apa?** Foto galeri yang di-upload lewat admin (Filament) tampil rusak/broken, tapi foto bawaan (seeder) aman.

**Kenapa bisa terjadi?** Foto bawaan menyimpan alamat lengkap (https://...), sedangkan hasil upload menyimpan alamat pendek (galeri/file.jpg) tanpa awalan `storage/`.

**Gimana cara memperbaikinya?** Di 3 file tampilan ditambahkan pengecekan: kalau alamat lengkap (http) → tampilkan apa adanya; kalau alamat pendek → ditambahkan awalan `storage/` secara otomatis.

---

## Perbaikan Sesi 5 (11 Agustus 2026) — Satu Tempat untuk Ubah Foto Galeri & Layanan

### 22. Cara mengubah foto galeri & layanan tidak seragam

**Bug-nya apa?** Untuk mengubah gambar galeri harus menjalankan pembangunan ulang database (reseed) — dan itu bisa bikin data ganda. Sedangkan untuk layanan cukup edit satu file.

**Kenapa bisa terjadi?** Gambar layanan dibaca langsung saat tampil (dari file pusat), tapi gambar galeri disimpan permanen ke database — jadi untuk mengubahnya harus menghitung ulang database.

**Gimana cara memperbaikinya?** Semua disamakan caranya: cukup edit file `StaticContent.php` (tabel `$galeriFotoMap`), tanpa perlu reseed dan tanpa cache. Gambar galeri hasil seed punya judul yang dikenali, jadi otomatis terhubung ke gambar di file pusat. Galeri buatan admin (judul tidak dikenal) tetap memakai fotonya sendiri.

| Gambar | Ubah di `StaticContent.php` | Perlu reseed? |
|--------|------------------------------|---------------|
| Galeri | tabel `$galeriFotoMap` | Tidak |
| Layanan | `LAYANAN_FALLBACK_IMAGES` | Tidak |

---

## Perbaikan Sesi 6 (19–21 Agustus 2026) — Keranjang Stabil & Tampilan Rapi

### 23. Tombol "Masukkan ke Keranjang" dan "Pesan" sering bermasalah

**Bug-nya apa?** Di dashboard customer, tombol "Keranjang" dan "Pesan" kadang tidak jalan, tidak ada notifikasi, dan jumlah barang di keranjang lama muncul.

**Kenapa bisa terjadi?** Tombol memakai "jalur khusus" (API) yang butuh tanda pengenal (token) dan berbeda dari cara kerja halaman lain, sehingga gampang gagal dan tidak sinkron.

**Gimana cara memperbaikinya?** Tombol diganti memakai jalur standar (cukup login biasa), sama seperti halaman lainnya. Angka di ikon keranjang sekarang dihitung otomatis oleh sistem setiap halaman dibuka — tanpa menunggu refresh.

---

### 24. Total harga keranjang tidak sesuai saat jumlah diubah

**Bug-nya apa?** Saat jumlah paket di keranjang diubah, total harga bisa tidak pas atau formatnya berantakan.

**Kenapa bisa terjadi?** Halaman keranjang memakai jalur hitung yang berbeda dari jalur penyimpanannya, jadi hasilnya tidak sinkron.

**Gimana cara memperbaikinya?** Keduanya disamakan ke satu jalur yang sama. Sekarang setiap ubah jumlah, harga satuan dan total langsung tampil benar di semua halaman.

---

### 25. Database keranjang membawa data yang tidak terpakai

**Bug-nya apa?** Tabel keranjang punya kolom yang tidak pernah dipakai, berpotensi jadi sumber error.

**Kenapa bisa terjadi?** Kolom itu sisa desain lama; isi keranjang (paketnya) sudah disimpan di tabel lain.

**Gimana cara memperbaikinya?** Kolom yang tidak terpakai dihapus. Definisi awal dibersihkan (untuk database baru) dan dibuatkan perbaikan khusus untuk database lama, tanpa menghapus data keranjang pengguna.

---

### 26. Harga layanan tampil "Rp 0"

**Bug-nya apa?** Tiga layanan utama (Variasi Mobil, Kaca Film, Audio Mobil) harganya 0, jadi total keranjang bisa Rp 0.

**Kenapa bisa terjadi?** Saat data dibuat, kolom harga sengaja diisi 0 untuk diisi manual — tapi tidak pernah diisi.

**Gimana cara memperbaikinya?** Harga diisi: Variasi Mobil Rp 1.500.000, Kaca Film Rp 200.000, Audio Mobil Rp 2.000.000. (Database lama perlu diperbarui lewat menu admin Layanan atau reset ulang.)

---

### 27. Halaman Galeri & Profil "keluar" dari tampilan dashboard saat login

**Bug-nya apa?** Pengguna yang sudah login membuka Galeri/Profil tetap melihat tampilan publik (seperti tamu).

**Kenapa bisa terjadi?** Kedua halaman selalu memakai kerangka tampilan publik, tanpa peduli status login.

**Gimana cara memperbaikinya?** Sistem mengecek status login: sudah login → tampil dengan kerangka dashboard (ada menu sampingnya); belum login → tampil sebagai halaman umum.

---

### 28. Tombol filter galeri kurang jelas & warnanya hilang

**Bug-nya apa?** Kategori filter kadang tidak cocok dengan isi, dan tombol "All Works" warnanya bisa tidak muncul.

**Kenapa bisa terjadi?** Kategori diambil otomatis dari data foto, dan warnanya memakai istilah yang tidak dikenali sistem.

**Gimana cara memperbaikinya?** Kategori dijadikan tetap 3: Variasi Mobil, Kaca Film, Audio Mobil. Warna tombol memakai warna yang sudah pasti ada, mengikuti tema situs.

---

### 29. Beberapa ikon tidak muncul (kotak kosong)

**Bug-nya apa?** Beberapa ikon tampil kosong di dashboard dan halaman Profil.

**Kenapa bisa terjadi?** Nama ikon yang dipakai salah ketik dan tidak dikenal kumpulan ikon (Phosphor).

**Gimana cara memperbaikinya?** Nama ikon diperbaiki (`ph-sparkles` → `ph-sparkle`, `ph-gem` → `ph-diamond`), sehingga semua ikon tampil normal.

---

### 30. Ada kode tampilan yang dobel

**Bug-nya apa?** Ada 2 file tampilan paket layanan yang hampir sama — satu terpakai, satu tidak.

**Kenapa bisa terjadi?** File lama tidak dibuang saat file baru dibuat.

**Gimana cara memperbaikinya?** File yang tidak terpakai dihapus, tinggal satu file — memudahkan perbaikan berikutnya.

---

### 31. Penambahan aset gambar

Ada 2 gambar pendukung baru: `master_craft_texture.png` dan `studio_network_map.png`.

---

## 💡 Cara Gampang Mengelola Website

| Yang Mau Dilakukan | Caranya |
|-------------------|---------|
| Ganti teks (nama toko, judul, tombol) | Edit file `app/Helpers/StaticContent.php` |
| Kelola galeri (tambah/ubah/hapus foto) | Login admin → menu Galeri |
| Kelola layanan (termasuk harga & foto) | Login admin → menu Layanan |
| Ubah gambar galeri/layanan tanpa ribet | Edit `StaticContent.php` (tabel `$galeriFotoMap` & `LAYANAN_FALLBACK_IMAGES`) |
| Reset data dari awal | Jalankan `php artisan migrate:fresh --seed` |


# Catatan Perbaikan — informasi_pemesanan_wrapping

**Tanggal:** 23 Agustus 2026
**Status:** Semua perubahan belum di-commit (masih di working directory)
**Total:** 24 file berubah + 3 file baru

---

## Daftar Isi

1. [Registrasi & Login](#1-registrasi--login)
2. [Halaman Publik (Beranda, Tentang Kami, Welcome, Galeri)](#2-halaman-publik)
3. [Katalog & Layanan (Proteksi Tamu)](#3-katalog--layanan-proteksi-tamu)
4. [Kebijakan Privasi (Fitur Baru)](#4-kebijakan-privasi-fitur-baru)
5. [Keranjang & Checkout](#5-keranjang--checkout)
6. [Dashboard Customer](#6-dashboard-customer)
7. [Perbaikan Lainnya](#7-perbaikan-lainnya)
8. [Cara Pengujian](#8-cara-pengujian)

---

## 1. Registrasi & Login

### 1a. Hapus Syarat Simbol pada Password

Sebelumnya, saat mendaftar password **wajib mengandung simbol** (!@#$% dll).
Sekarang syarat simbol dihapus. Yang tetap berlaku: min 9 karakter,
maks 20 karakter, wajib ada huruf kapital dan huruf kecil.

**File yang diubah:**

| File | Baris | Detail |
|---|---|---|
| `app/Http/Controllers/Auth/RegisteredUserController.php` | 42 | Regex validasi: `(?=.*[!@#$%^&*()...])` dihapus → menjadi `'regex:/^(?=.*[a-z])(?=.*[A-Z]).{9,20}$/'` |
| `app/Http/Controllers/Auth/RegisteredUserController.php` | 60 | Pesan error: *"…1 huruf kapital, 1 huruf kecil, dan 1 simbol."* → *"Kata sandi harus mengandung minimal 1 huruf kapital dan 1 huruf kecil."* |
| `app/Http/Requests/Auth/RegisterRequest.php` | 25 | Sama dengan baris 42 di atas (versi API) |
| `app/Http/Requests/Auth/RegisterRequest.php` | 48 | Sama dengan baris 60 di atas (versi API) |
| `resources/views/auth/register.blade.php` | 109–111 *(dihapus)* | Item checklist **"○ Mengandung simbol (!@#$%^&*)"** versi mobile dihapus |
| `resources/views/auth/register.blade.php` | 250–252 *(dihapus)* | Item checklist yang sama versi desktop dihapus |
| `resources/views/auth/register.blade.php` | 394 *(dihapus)* | Deklarasi variabel JS `chkSymbol` dihapus |
| `resources/views/auth/register.blade.php` | 400 *(dihapus)* | Baris JS pengecekan simbol di fungsi `valPassword()` dihapus |

### 1b. Tombol "Dashboard" di Halaman Login & Daftar

Tombol ikon **X (tutup)** di pojok kanan atas diganti menjadi tombol teks
**"DASHBOARD"** dengan gaya kotak bergaris.

| File | Baris | Detail |
|---|---|---|
| `resources/views/auth/login.blade.php` | 27–29 | Ikon `<i class="ph ph-x">` diganti tombol teks "Dashboard" |
| `resources/views/auth/register.blade.php` | 27–29 | Sama seperti login |

---

## 2. Halaman Publik

### 2a. Link WhatsApp Hardcoded Diganti Nomor Dinamis

Semua link WhatsApp yang sebelumnya **ditulis manual** (`https://wa.me/628123456789`)
diganti agar mengambil nomor dari database (diatur lewat admin), sehingga
kalau nomor perusahaan berubah tidak perlu edit kode lagi.

| File | Baris | Detail |
|---|---|---|
| `resources/views/landing/tentang-kami/_cta.blade.php` | 18–19 | `https://wa.me/628123456789` → `{{ $profil->whatsapp_link }}` |
| `resources/views/landing/beranda/_cta-langkah.blade.php` | 21–23 | Sama seperti di atas + ikon tombol diganti dari logo WhatsApp menjadi ikon tag |
| `app/Providers/SettingsServiceProvider.php` | 64 | Fallback: kalau nomor WA kosong, link tadinya `#` (mati) → sekarang pakai konstanta `StaticContent::COMPANY_WHATSAPP` |

### 2b. Arah Tombol CTA Diubah ke Katalog

| File | Baris | Detail |
|---|---|---|
| `resources/views/landing/beranda/_hero.blade.php` | 29 | Tombol "Pesan Sekarang": dulu langsung ke WhatsApp → sekarang dinamis (sudah login → halaman katalog user; belum login → halaman layanan) |
| `resources/views/landing/beranda/_keunggulan.blade.php` | 69 | Tombol "Cek Syarat": link WA hardcoded → route `katalog.user` |
| `resources/views/welcome.blade.php` | 21 | Tombol "Get Started Now": `wa.me` → route `katalog.user` |
| `resources/views/welcome.blade.php` | 121 | Tombol "Hubungi Tim Kami": `wa.me` → route `katalog.user` |
| `resources/views/landing/beranda/_cta-langkah.blade.php` | 25, 28 | Tombol "Pelajari" **dinonaktifkan** (dibungkus komentar `{{-- --}}`) |

### 2c. Perapian Tampilan Galeri

| File | Baris | Detail |
|---|---|---|
| `resources/views/landing/galeri/_hero.blade.php` | 5 | Padding hero galeri diperkecil (`py-16 sm:py-20` → `py-10 sm:py-14`, margin bawah `mb-12` → `mb-8`) |
| `resources/views/landing/galeri/index.blade.php` | 6 | Padding atas konten dikurangi (`py-12` → `pt-4 pb-12`) untuk tampilan tamu |

---

## 3. Katalog & Layanan (Proteksi Tamu)

### Konsepnya:

Pengunjung yang **belum login (tamu)** tidak bisa langsung menambahkan paket
ke keranjang. Kalau tamu menekan tombol "Book Discovery Call" atau tombol
tambah (+), yang muncul adalah **modal ajakan daftar akun**
(`showRegisterPrompt()`). Hanya user yang sudah login yang bisa add-to-cart.

### File baru (komponen modal):

| File | Keterangan |
|---|---|
| `resources/views/components/register-prompt.blade.php` | **FILE BARU.** Modal promosi registrasi + animasi. Berisi fungsi global `window.showRegisterPrompt()` (baris 37–38) |

### File yang diubah:

| File | Baris | Detail |
|---|---|---|
| `resources/views/landing/katalog/_grid.blade.php` | 88–104 | Kartu wide: form add-to-cart dibungkus `@auth`; tamu dapat tombol `showRegisterPrompt()` (baris 100) |
| `resources/views/landing/katalog/_grid.blade.php` | 159–176 | Kartu medium: sama (tombol guest di baris 171) |
| `resources/views/landing/katalog/_grid.blade.php` | 242–259 | Kartu kecil: sama (tombol guest di baris 254) |
| `resources/views/landing/katalog/_grid.blade.php` | 275–278 | Ditambah `<x-register-prompt />` + perbaikan indentasi `@endif` |
| `resources/views/landing/katalog/index.blade.php` | 6–10 *(dihapus)* | Spacer kosong tinggi 112px untuk tampilan tamu dihapus |
| `resources/views/landing/katalog/index.blade.php` | 22–28 | CSS animasi modal (`modal-in`) ditambahkan |
| `resources/views/landing/katalog/index.blade.php` | 40–41 | Include `_grid` **diganti** `_packages-vertical` |
| `resources/views/landing/katalog/_packages-vertical.blade.php` | **FILE BARU** (±12KB) | Tampilan katalog baru: daftar paket vertikal menggantikan grid. Tombol guest di baris 118 & 122 |
| `resources/views/landing/layanan/_grid.blade.php` | 87 | Pembungkus `@auth` ditambahkan |
| `resources/views/landing/layanan/_grid.blade.php` | 92–98 | Tamu: tombol "Lihat Paket" jadi tombol `showRegisterPrompt()` (baris 93) |
| `resources/views/landing/layanan/_grid.blade.php` | 113 | Ditambah `<x-register-prompt />` |
| `resources/views/landing/layanan/index.blade.php` | 25–34 | CSS animasi modal-in ditambahkan |

---

## 4. Kebijakan Privasi (Fitur Baru)

Fitur halaman **Kebijakan Privasi** ditambahkan, sebelumnya link footer
hanya menunjuk ke `#` (tidak ke mana-mana).

| File | Baris | Detail |
|---|---|---|
| `resources/views/landing/kebijakan-privasi/index.blade.php` | **FILE BARU** (±7KB) | Halaman kebijakan privasi lengkap dengan kontennya |
| `app/Http/Controllers/DashboardController.php` | 29–32 | Method `layanan()`: kalau user sudah login, otomatis dialihkan ke katalog user |
| `app/Http/Controllers/DashboardController.php` | 36–39 | **Method baru** `kebijakanPrivasi()` untuk menampilkan halaman |
| `routes/web.php` | 47 | Route baru: `GET /kebijakan-privasi` → nama route `kebijakan-privasi` |
| `routes/web.php` | 108 | Redirect "paket tidak ditemukan": dulu ke `layanan` → sekarang ke `katalog.user` |
| `routes/web.php` | 121 | Redirect keranjang kosong: sama, dulu `layanan` → `katalog.user` |
| `resources/views/layouts/tampilan_utama.blade.php` | 5 | Route `kebijakan-privasi` masuk daftar `$is_frontend` (agar memakai navbar/footer frontend) |
| `resources/views/layouts/tampilan_utama.blade.php` | 247–248 | Footer: link "Layanan" dulu ke `katalog.user` → sekarang ke `layanan`; link "Kebijakan Privasi" dulu `#` → aktif ke routenya |

---

## 5. Keranjang & Checkout

### 5a. Notifikasi Diganti Sistem Toast

Pesan sukses/error setelah aksi keranjang ditambah / dihapus / dikosongkan
diganti dari flash message biasa menjadi **toast notification**.

| File | Baris | Detail |
|---|---|---|
| `app/Http/Controllers/KeranjangController.php` | 55 | `'error'` → `'toast_error'` (batas maksimal 3 paket) |
| `app/Http/Controllers/KeranjangController.php` | 84 | `'success'` → `'toast_success'` (paket ditambahkan) |
| `app/Http/Controllers/KeranjangController.php` | 102 | `'success'` → `'toast_success'` (item dihapus) |
| `app/Http/Controllers/KeranjangController.php` | 119 | `'success'` → `'toast_success'` (keranjang dikosongkan) |
| `app/Http/Controllers/KeranjangController.php` | 165 | `'success'` → `'toast_success'` (jumlah unit diperbarui) |
| `resources/views/dashboard/customer/keranjang/index.blade.php` | 390–400 *(baru)* | Jembatan toast: flash message dari controller ditampilkan via fungsi `showToast()` |

### 5b. Dialog Konfirmasi Browser Diganti Modal Custom

Pop-up konfirmasi bawaan browser (`confirm()`) yang tampilannya kasar
diganti **modal custom** bergaya aplikasi (ada animasi, ikon tempat sampah,
tombol Batal/Hapus).

| File | Baris | Detail |
|---|---|---|
| `resources/views/dashboard/customer/keranjang/index.blade.php` | 37 | Kosongkan keranjang: `confirm('...')` → `confirmEmptyCart(event, this)` |
| `resources/views/dashboard/customer/keranjang/index.blade.php` | 100 | Hapus item: `confirm('...')` → `confirmDeleteItem(event, this, 'nama layanan')` |
| `resources/views/dashboard/customer/keranjang/index.blade.php` | ±415–475 *(baru)* | HTML modal konfirmasi + fungsi JS `confirmEmptyCart()` dan `confirmDeleteItem()` |

### 5c. Diskon Member & Kode Promo Dihapus

Bagian ringkasan belanja yang menampilkan **DISKON MEMBER** (potongan 5%
maks Rp1,5jt jika subtotal di atas Rp10jt) dan kotak input **KODE PROMO**
dihapus seluruhnya — baik di sisi PHP maupun JavaScript-nya.
Grand total sekarang = subtotal + biaya layanan saja.

| File | Baris | Detail |
|---|---|---|
| `resources/views/dashboard/customer/keranjang/index.blade.php` | 193–223 *(dihapus)* | Blok PHP hitungan diskon + tampilan "DISKON MEMBER" dihapus |
| `resources/views/dashboard/customer/keranjang/index.blade.php` | 232–245 *(dihapus)* | Kotak "KODE PROMO" (input + tombol Terapkan) dihapus |
| `resources/views/dashboard/customer/keranjang/index.blade.php` | 311, 319–345 *(dihapus)* | Referensi variabel diskon di JS (`summary-discount`, rumus potongan) dihapus |
| `resources/views/dashboard/customer/keranjang/index.blade.php` | 278, 311 | Rumus grand total disederhanakan: `subtotal + serviceCharge` |

### 5d. Checkout: Lokasi Pengerjaan Disembunyikan

| File | Baris | Detail |
|---|---|---|
| `resources/views/dashboard/customer/pesanan/checkout.blade.php` | 122 | Kolom pilihan lokasi pengerjaan diberi class `hidden` (disembunyikan); nilai default tetap terkirim sebagai `"toko"` lewat hidden input |

---

## 6. Dashboard Customer

### 6a. Carousel Paket

Tombol panah kiri/kanan pada carousel paket dihapus. Scroll sekarang
langsung digeser/swipe manual, dengan class `overflow-x-auto` ditambahkan
supaya bisa discroll di semua perangkat.

| File | Baris | Detail |
|---|---|---|
| `resources/views/dashboard/customer/dashboard/_packages-carousel.blade.php` | 14 | Class `overflow-x-auto` ditambahkan ke wrapper slider |
| `resources/views/dashboard/customer/dashboard/_packages-carousel.blade.php` | 116–127 *(dihapus)* | Blok tombol navigasi panah (`carousel-prev` / `carousel-next`) dihapus |

### 6b. Panel Notifikasi Responsif di HP

Posisi panel dropdown notifikasi diperbaiki: tadinya absolute (posisi bisa
meleset di layar kecil), sekarang fixed dan menyesuaikan lebar layar HP.

| File | Baris | Detail |
|---|---|---|
| `resources/views/layouts/dashboard_customer.blade.php` | 168 | Panel `#notif-panel`: `absolute right-0 w-80` → `fixed top-20 inset-x-4 sm:left-1/2 sm:-translate-x-1/2 sm:w-96` |

---

## 7. Perbaikan Lainnya

| File | Baris | Detail |
|---|---|---|
| `app/Providers/SettingsServiceProvider.php` | 64 | Fallback link WhatsApp dari `'#'` → `\App\Helpers\StaticContent::COMPANY_WHATSAPP` (nomor default perusahaan) — lihat juga bagian 2a |

### Ringkasan File Baru

| File | Fungsi |
|---|---|
| `resources/views/components/register-prompt.blade.php` | Modal ajakan daftar akun untuk tamu |
| `resources/views/landing/katalog/_packages-vertical.blade.php` | Tampilan katalog daftar vertikal (pengganti grid) |
| `resources/views/landing/kebijakan-privasi/index.blade.php` | Halaman kebijakan privasi |

---

## 8. Cara Pengujian

Setelah semua perubahan, uji hal-hal berikut:

1. **Registrasi tanpa simbol** — buat akun dengan password `Password123`
   (tanpa simbol) → harusnya **berhasil**. Checklist di halaman daftar juga
   tidak lagi menampilkan syarat simbol.
2. **Tamu di katalog/layanan** — buka katalog tanpa login, klik tombol Book
   atau (+) → harusnya muncul **modal ajakan daftar**, bukan masuk keranjang.
3. **Link WhatsApp** — cek tombol WA di beranda & tentang kami → harusnya
   membuka nomor dari database admin, bukan nomor lama yang hardcoded.
4. **Footer** — klik "Kebijakan Privasi" → halaman kebijakan privasi terbuka;
   klik "Layanan" → masuk ke halaman layanan.
5. **Keranjang** — tambah/hapus/kosongkan item → notifikasi muncul sebagai
   toast; hapus item memunculkan modal konfirmasi custom; ringkasan belanja
   **tidak ada lagi diskon member & kode promo**.
6. **Checkout** — kolom lokasi pengerjaan tidak tampak, tapi nilai "toko"
   tetap tersimpan saat order dibuat.
7. **Dashboard customer** — carousel paket bisa digeser manual; panel
   notifikasi rapi saat dibuka dari HP.