
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


Catatan :
/**
 * Alur Kerja Live Search Katalog:
 * 1. Ambil input nilai pencarian dan bersihkan spasi berlebih (.trim()).
 * 2. Loop semua elemen '.katalog-item'.
 * 3. Ambil teks judul (.katalog-title) & deskripsi (.katalog-desc).
 * 4. Uji apakah query pencarian ada pada judul ATAU deskripsi.
 * 5. Tampilkan/Sembunyikan kartu dengan kelas transisi CSS.
 * 6. Jika semua kartu tersembunyi, tampilkan pesan 'tidak ditemukan'.
 */



# Fitur Baru: Rating oleh User — Jobdesk Backend & Frontend

> **Status:** Rencana pengembangan (belum dieksekusi / belum ada kode).
> Fitur yang belum dikerjakan ini dicatat sebagai pembagian tugas supaya
> pengerjaan nantinya jelas: mana tanggung jawab **Backend** dan mana
> tanggung jawab **Frontend**.

---

## Diskusi Alur Rating (7 September 2026)

### Konteks

Mitra sudah melayani banyak pesanan **sebelum** sistem ini ada. Pelanggan lama
ingin memberikan rating/testimoni melalui sistem, tetapi mereka **tidak punya
data pesanan** di database. Maka alur rating dipecah menjadi dua.

### Alur 1 — Pelanggan Baru (via Riwayat Pesanan)

Untuk pelanggan yang order melalui sistem, rating dilakukan dari riwayat pesanan.

```
Login → Dashboard → Riwayat Pesanan → Tab "Selesai"
→ Klik pesanan selesai → Tombol "Beri/Ubah Rating"
→ Form rating (bintang 1–5 + ulasan + upload foto) → Simpan
```

- Rating **terikat ke pesanan** (`id_pesanan` terisi)
- **1 rating per pesanan** (unique constraint di `id_pesanan`)
- Customer **bisa mengubah (edit)** rating kapan saja

### Alur 2 — Pelanggan Lama (via Dropdown Layanan)

Untuk pelanggan yang sudah pernah pesan **sebelum sistem**, rating dilakukan
tanpa data pesanan. Cukup buat akun, lalu pilih layanan dari dropdown.

```
Belum punya akun → Daftar akun → Login
→ Buka form rating (dari dashboard atau halaman Testimoni)
→ Dropdown pilih layanan (Variasi Mobil / Kaca Film / Audio Mobil)
→ Rating bintang 1–5 + ulasan + upload foto → Simpan
```

- Rating **terikat ke layanan** (`id_layanan` terisi, `id_pesanan` kosong)
- **1 rating per layanan per akun** (unique constraint di `id_user` + `id_layanan`)
- Customer **bisa mengubah (edit)** rating kapan saja

### Keputusan Diskusi

| No | Keputusan | Keterangan |
|----|-----------|------------|
| 1 | Dua alur rating | Alur 1 (via pesanan) + Alur 2 (via dropdown layanan) |
| 2 | Tidak ada verifikasi khusus untuk pelanggan lama | Cukup daftar akun + login |
| 3 | Dropdown layanan untuk pelanggan lama | 3 pilihan: Variasi Mobil, Kaca Film, Audio Mobil (dari tabel `layanans`) |
| 4 | Rating per layanan dibatasi 1x per akun | Unique constraint di (`id_user` + `id_layanan`) untuk alur 2 |
| 5 | Tabel `testimonis` lama TIDAK dipakai | Dibuat tabel baru `ratings` + `rating_medias` |
| 6 | Media per rating | Maks. **2 foto** (opsional), format **jpg/jpeg/png/webp**, maks. **5MB/foto** |
| 7 | Rating langsung tampil publik | Tanpa persetujuan admin, admin bisa hapus jika tidak pantas |
| 8 | Halaman Testimoni publik | `/testimoni` — rata-rata bintang, filter, sort, daftar ulasan |

### Dampak Teknis terhadap Schema `ratings`

Karena ada dua alur, tabel `ratings` harus mendukung keduanya:

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| `id` | bigint (PK) | Auto-increment |
| `id_user` | FK → `users.id` | Wajib, cascade delete |
| `id_pesanan` | FK → `pesanans.id_pesanan` | **Nullable** (hanya terisi untuk Alur 1) |
| `id_layanan` | FK → `layanans.id_layanan` | **Nullable** (hanya terisi untuk Alur 2) |
| `rating` | tinyint (1–5) | Wajib |
| `ulasan` | text | Nullable (opsional) |
| `is_tampil` | bool | Default true, untuk sembunyikan rating |
| `timestamps` | | created_at, updated_at |

**Unique constraint:**
- Alur 1: `id_pesanan` unique (null diabaikan) → 1 rating per pesanan
- Alur 2: (`id_user` + `id_layanan`) unique (null diabaikan) → 1 rating per layanan per akun

---

## Ringkasan Fitur (Keputusan yang Sudah Disepakati)

- Customer memberi **rating bintang 1–5** + **ulasan teks** (opsional) +
  **unggah foto** (maks. **2 foto**, opsional).
- **Dua alur rating:**
  - **Alur 1 (Pelanggan Baru):** Rating dari riwayat pesanan yang berstatus `selesai`.
    1 rating per pesanan (`id_pesanan` wajib, unique).
  - **Alur 2 (Pelanggan Lama):** Rating dari dropdown layanan tanpa data pesanan.
    Cukup daftar akun + login. 1 rating per layanan per akun
    (`id_layanan` wajib untuk alur ini, unique per user+layanan).
- Customer **bisa mengubah (edit) ratingnya** kapan saja (kedua alur).
- Rating **langsung tampil publik** tanpa persetujuan admin. Admin tetap bisa
  menghapus rating bila tidak pantas.
- Dikumpulkan di **halaman Testimoni** baru (`/testimoni`) — berisi rata-rata
  bintang, filter bintang (Semua/5/4/3/2/1), urut (Terbaru/Tertinggi), dan
  daftar kartu ulasan.
- Navbar menampilkan menu **Testimoni**. Beranda **tidak** menampilkan seksi ulasan.
- Fitur disediakan **via web (Blade)** **dan** via **REST API (Sanctum)**.
- Nama entitas: tabel `ratings` + `rating_medias` (tabel `testimonis` lama TIDAK dipakai).

---

## A. Jobdesk BACKEND (PHP / Laravel / Database / API / Admin)

Tugas di sisi data, logika, dan keamanan. Umumnya dikerjakan oleh pengembang backend.

### A1. Database (Migration)

| # | Tugas | File | Detail |
|---|-------|------|--------|
| 1 | Buat tabel `ratings` | `database/migrations/<timestamp>_create_ratings_table.php` | Kolom: `id`, `id_user` (FK→`users.id`, cascade), `id_pesanan` (FK→`pesanans.id_pesanan`, nullable, cascade), `id_layanan` (FK→`layanans.id_layanan`, nullable, cascade), `rating` (tinyint 1–5, wajib), `ulasan` (text, nullable), `is_tampil` (bool, default `true`), `timestamps`. **Unique:** `id_pesanan` (null diabaikan); (`id_user`, `id_layanan`) (null diabaikan) |
| 2 | Buat tabel `rating_medias` | `database/migrations/<timestamp>_create_rating_medias_table.php` | Kolom: `id`, `id_rating` (FK→`ratings.id`, cascade), `path` (string), `urutan` (int), `timestamps`. **Tipe selalu `image`** (tidak ada video) |

### A2. Model & Relasi

| # | Tugas | File | Detail |
|---|-------|------|--------|
| 3 | Buat model `Rating` | `app/Models/Rating.php` | `fillable`: `id_user`, `id_pesanan`, `id_layanan`, `rating`, `ulasan`, `is_tampil`. `casts`: `rating` → integer, `is_tampil` → boolean. Relasi: `user()` belongsTo User, `pesanan()` belongsTo Pesanan (nullable), `layanan()` belongsTo Layanan (nullable), `medias()` hasMany RatingMedia. `booted()` hapus cache `testimoni_ratings` saat `saved`/`deleted` (pola `Layanan`) |
| 4 | Buat model `RatingMedia` | `app/Models/RatingMedia.php` | `fillable`: `id_rating`, `path`, `urutan`. Relasi `rating()` belongsTo Rating |
| 5 | Tambah relasi di `Pesanan` | `app/Models/Pesanan.php` | Tambah method `rating()` → `hasOne(Rating::class, 'id_pesanan', 'id_pesanan')` |
| 6 | Tambah relasi di `Layanan` | `app/Models/Layanan.php` | Tambah method `ratings()` → `hasMany(Rating::class, 'id_layanan', 'id_layanan')` |

### A3. Service Layer

| # | Tugas | File | Detail |
|---|-------|------|--------|
| 7 | Buat `RatingService` | `app/Services/RatingService.php` | **Method `storeForPesanan(Pesanan, data, files)`** (Alur 1): validasi rating 1–5, ulasan maks. 500 karakter, foto maks. 2 file, image **jpg/jpeg/png/webp** maks. **5MB/foto**, simpan ke `storage/app/public/rating/`, buat record `ratings` + `rating_medias`. **Method `storeForLayanan(Layanan, user, data, files)`** (Alur 2): sama validasinya, cek unique (`id_user`+`id_layanan`), kalau sudah ada → update. **Method `update(Rating, data, files)`**: update data, replace media lama. **Method `deleteMedia(RatingMedia)`**: hapus file fisik + record. Daftarkan sebagai singleton di `AppServiceProvider` |
| 8 | Cache | `app/Services/CacheService.php` | Tambah key cache `testimoni_ratings` (data daftar ulasan untuk halaman publik) |

### A4. Event, Listener & Notifikasi

| # | Tugas | File | Detail |
|---|-------|------|--------|
| 9 | Buat event `RatingCreated` & `RatingUpdated` | `app/Events/RatingCreated.php`, `app/Events/RatingUpdated.php` | Membawa instance `Rating` |
| 10 | Buat listener `NotifyAdminRating` | `app/Listeners/NotifyAdminRating.php` | Notifikasi ke admin: baris `notifikasis` (in-app) + Filament database notification (pola notifikasi order yang sudah ada) |
| 11 | Daftarkan event-listener | `app/Providers/EventServiceProvider.php` | Mapping `RatingCreated` & `RatingUpdated` → `NotifyAdminRating` |

### A5. Authorization (Policy)

| # | Tugas | File | Detail |
|---|-------|------|--------|
| 12 | Buat `RatingPolicy` | `app/Policies/RatingPolicy.php` | **Alur 1 (`create`/`update` via pesanan):** pesanan milik user yang login **dan** berstatus `selesai`. **Alur 2 (`create`/`update` via layanan):** user yang login, belum punya rating untuk layanan itu. Daftarkan di `AppServiceProvider` (ikuti pola `PesananPolicy`) |

### A6. Web Route & Controller (Customer)

| # | Tugas | File | Detail |
|---|-------|------|--------|
| 13 | Route rating (Alur 1 — via pesanan) | `routes/web.php` | Dalam grup `pesanan` (terproteksi auth+verified): `GET /pesanan/{id}/rating` → `RatingController@form`; `POST /pesanan/{id}/rating` → `RatingController@store`; `PUT /pesanan/{id}/rating` → `RatingController@update` |
| 14 | Route rating (Alur 2 — via layanan) | `routes/web.php` | Terproteksi auth+verified: `GET /rating/buat` → `RatingController@formLayanan`; `POST /rating/buat` → `RatingController@storeLayanan`; `PUT /rating/{id}/ubah` → `RatingController@updateLayanan` |
| 15 | Route testimoni publik | `routes/web.php` | `GET /testimoni` → `TestimoniController@index` (`testimoni.index`) |
| 16 | Buat `RatingController` | `app/Http/Controllers/RatingController.php` | **Alur 1:** `form($id_pesanan)` — cek pesanan milik user + status selesai + sudah rating?, render form. `store($id_pesanan)` — delegasi ke `RatingService->storeForPesanan()`, redirect toast. `update($id_pesanan)` — delegasi ke `RatingService->update()`, redirect toast. **Alur 2:** `formLayanan()` — ambil daftar layanan dari cache, cek apakah user sudah rate tiap layanan, render form dengan dropdown. `storeLayanan()` — delegasi ke `RatingService->storeForLayanan()`, redirect toast. `updateLayanan($id)` — delegasi ke `RatingService->update()`, redirect toast |
| 17 | Buat `TestimoniController` | `app/Http/Controllers/TestimoniController.php` | Method `index`: ambil dari cache `testimoni_ratings` (publik, `is_tampil = true`), dukung filter bintang & sort, hitung rata-rata bintang, render `landing.testimoni.index` |

### A7. REST API (Sanctum)

| # | Tugas | File | Detail |
|---|-------|------|--------|
| 18 | Route API rating | `routes/api.php` | Protected (`auth:sanctum`): `POST /api/pesanan/{pesanan}/rating` (Alur 1), `POST /api/rating/layanan` (Alur 2), `PUT /api/rating/{rating}` (edit). Publik: `GET /api/rating` (daftar ulasan publik + media) |
| 19 | Buat `Api\RatingController` | `app/Http/Controllers/Api/RatingController.php` | Method `storeByPesanan`, `storeByLayanan`, `update`, `index` (publik). Validasi server-side, respons JSON 201/200, error 401/403/422 |

### A8. Admin Panel (Filament)

| # | Tugas | File | Detail |
|---|-------|------|--------|
| 20 | Buat `RatingResource` | `app/Filament/Resources/Ratings/RatingResource.php` (+ Pages `ListRatings`, `ViewRating`) | Kolom: user (nama), tipe (Pesanan/Layanan), kode pesanan/nama layanan, bintang, ulasan, jumlah preview foto, `is_tampil`, tanggal. Aksi: lihat detail, hapus, toggle `is_tampil`. **Tanpa** create/edit manual |

### A9. Pengujian (Pest)

| # | Tugas | File | Detail |
|---|-------|------|--------|
| 21 | Buat feature test | `tests/Feature/RatingFeatureTest.php` | **Alur 1:** unauth → 401, pesanan bukan `selesai`/bukan miliknya → error, sukses → 201, duplikat per pesanan → ditolak, edit berhasil, validasi foto (tipe/ukuran/jumlah). **Alur 2:** unauth → 401, sukses → 201, duplikat user+layanan → ditolak (update), edit berhasil. **Publik:** daftar hanya `is_tampil = true` |

### A10. Penyelesaian Backend

- Jalankan: `php artisan migrate`, `./vendor/bin/pint` (format kode), `php artisan test`.

---

## B. Jobdesk FRONTEND (Blade / Tailwind / Alpine.js / JavaScript)

Tugas di sisi tampilan dan interaksi pengguna. Umumnya dikerjakan oleh pengembang frontend.

### B1. Form Rating — Alur 1 (via Riwayat Pesanan)

| # | Tugas | File | Detail |
|---|-------|------|--------|
| 1 | Buat halaman form rating pesanan | `resources/views/dashboard/customer/pesanan/rating.blade.php` (FILE BARU) | Extend `layouts.dashboard_customer`. Terima `$pesanan` + `$rating` (nullable, untuk mode edit). **Star picker 1–5** (Alpine.js), textarea ulasan (opsional, maks. 500 karakter), upload foto (maks. 2, preview thumbnail), tombol Simpan. Mode create → POST, mode edit → PUT + pre-fill data lama. Tampil pesan "Terima kasih atas ulasan Anda" jika sudah rated |

### B2. Form Rating — Alur 2 (via Dropdown Layanan)

| # | Tugas | File | Detail |
|---|-------|------|--------|
| 2 | Buat halaman form rating layanan | `resources/views/dashboard/customer/rating/layanan.blade.php` (FILE BARU) | Extend `layouts.dashboard_customer`. **Dropdown layanan** (3 opsi dari controller, disabled jika sudah rate). Star picker, textarea, upload foto (maks. 2, preview), tombol Simpan. Mode edit: tampil data lama + foto yang sudah diupload |

### B3. Tombol Rating di Riwayat & Detail Pesanan

| # | Tugas | File | Detail |
|---|-------|------|--------|
| 3 | Tombol "Beri/Ubah Rating" di daftar pesanan | `resources/views/dashboard/customer/pesanan/index.blade.php` | Untuk status `selesai`: tampilkan tombol **"Beri Rating"** (belum ada rating) atau **"Ubah Rating"** (sudah ada) → link ke form B1. Ikon bintang |
| 4 | Tombol "Beri/Ubah Rating" di detail pesanan | `resources/views/dashboard/customer/pesanan/show.blade.php` | Di blok status `selesai` tambahkan tombol menuju form rating (pola tombol "Unduh Invoice PDF" yang sudah ada) |

### B4. Akses Form Rating Alur 2 dari Dashboard

| # | Tugas | File | Detail |
|---|-------|------|--------|
| 5 | Tombol "Beri Testimoni" di dashboard customer | `resources/views/dashboard/customer/dashboard/index.blade.php` | Tambahkan section/card "Beri Testimoni" yang link ke `rating.buat` (Alur 2). Hanya tampil jika user sudah login. Ikon bintang, teks "Bagikan pengalaman Anda" |

### B5. Halaman Testimoni Publik

| # | Tugas | File | Detail |
|---|-------|------|--------|
| 6 | Buat halaman Testimoni | `resources/views/landing/testimoni/index.blade.php` (FILE BARU) | Extend `layouts.tampilan_utama`. Konten: heading, **blok rata-rata bintang** keseluruhan, **filter bintang** (Semua/5/4/3/2/1) + **sort** (Terbaru/Tertinggi), **daftar kartu ulasan**: bintang, teks ulasan, **nama layanan** (untuk Alur 2) atau **kode pesanan** (untuk Alur 1), nama user, tanggal, preview foto (`<img>`). Empty state saat belum ada ulasan |

### B6. Navigasi Landing Page

| # | Tugas | File | Detail |
|---|-------|------|--------|
| 7 | Tambah konstanta menu | `app/Helpers/StaticContent.php` | Tambah konstanta `NAV_TESTIMONI = 'Testimoni'` |
| 8 | Link navbar desktop & mobile | `resources/views/layouts/tampilan_utama.blade.php` | Tambah route `testimoni.index` ke daftar `$is_frontend`; tambah link menu **Testimoni** di navbar desktop (setelah "Tentang Kami") dan di menu mobile |

### B7. Penyelesaian Frontend

- Jalankan: `npm run build` (atau `npm run dev`), lalu uji tampilan di desktop & HP (responsive).

---

## C. Catatan Pengerjaan

- **Urutan pengerjaan:** Backend selesai dulu (A1–A9) agar data & API siap, baru Frontend (B1–B6) mengonsumsi.
- **Koordinasi Backend–Frontend:** kesepakatan nama variabel/kolom (`rating`, `ulasan`, `is_tampil`, `medias[]` dengan `path`), URL route, dan format tanggapan API.
- **Penyimpanan media:** folder `rating/` di disk `public` (`storage/app/public/rating/`), sudah didukung `php artisan storage:link`.
- **Keamanan:** semua form memakai CSRF; Alur 1 hanya untuk pesanan milik user & berstatus `selesai`; Alur 2 hanya untuk user yang login + belum rate layanan itu.
- **Tabel lama** `testimonis` **tidak** diubah/dipakai.
- **Media:** hanya foto (jpg/jpeg/png/webp), maks. 2 foto per rating, maks. 5MB per foto. Tidak ada video.

**Deviations saat implementasi (Backend A1–A10):**

- **`order_ref` bukan generated column.** Solusi C semula memakai `BIGINT UNSIGNED GENERATED ALWAYS AS (IFNULL(id_pesanan,0)) STORED`, tetapi MySQL 8.4.3 menolaknya (error 1215) ketika tabel juga punya FOREIGN KEY pada `id_pesanan`. Jadi `order_ref` dibuat sebagai kolom biasa `BIGINT UNSIGNED DEFAULT 0`, dan diisi otomatis oleh `RatingService::validateAndCreate()` (`order_ref = id_pesanan ?? 0`). Unique index tetap dua:
  - `unique('id_pesanan','id_layanan')` → Alur 1: 1 rating per layanan per pesanan.
  - `unique('id_user','id_layanan','order_ref')` → Alur 2: 1 rating per layanan per akun (tanpa pesanan, `order_ref = 0`).
- **Perbaikan migration lama `2026_08_19_115805_fix_keranjangs_drop_id_paket`**: `up()`/`down()` sekarang di-guard `Schema::hasColumn('keranjangs','id_paket')`. Sebelumnya migration ini mentargetkan kolom `id_paket` yang sudah tidak dibuat oleh `create_keranjangs`, sehingga `migrate:fresh` / test (RefreshDatabase) selalu gagal.
- **Test & role:** seeder role global di `tests/Pest.php` ternyata tidak aktif pada environment DB ini; `RatingFeatureTest` melakukan seeder `RolesTableSeeder` di `beforeEach` file-nya sendiri (agar listener notifikasi admin tidak error `RoleDoesNotExist`).
- **Listener `NotifyAdminRating`** dibuat defensif: jika role admin belum ada datanya, rating tetap tersimpan tanpa memblokir request.
- **Status uji:** `php artisan test --filter=RatingFeatureTest` = 12 passed (43 assertions). Dua kegagalan pre-existing di `Auth\AuthenticationTest` (logout) dan `Auth\RegistrationTest` tidak terkait fitur rating (perbedaan redirect `/` vs `/login`).

---

# Sesi Lanjutan Rating — 8 September 2026

> Status: Sebagian **sudah dieksekusi**, sebagian masih **rencana yang disepakati**.
> Sesi ini melanjutkan fitur rating yang sudah selesai di backend & form, fokus ke
> **titik masuk UI**, **tujuan redirect**, dan **keamanan/validasi konten**.

---

## 1. TOMBOL RATING DI RIWAYAT PESANAN — ✅ SUDAH DIEKSEKUSI

### Latar belakang

Fitur rating (backend + form) sudah lengkap, tapi **tidak ada tombol apa pun** yang
mengarah ke form rating dari halaman Riwayat Pesanan. Form hanya bisa diakses dengan
mengetik URL langsung (`/pesanan/{id}/rating` atau `/rating/buat`).

Hasil analisis:
- **Backend 100% lengkap**: model `Rating`/`RatingMedia`, migrasi (sudah jalan), service,
  controller web + API, event/listener, policy, Filament admin, 2 form rating, halaman
  testimoni publik, routes, dan 12 test feature — semuanya sudah ada.
- **Yang hilang hanya titik masuk UI** (task frontend B3 #3, B4 #5, B6 #7–8 dari dokumen
  sebelumnya): tombol rating di riwayat pesanan, tombol rating di detail pesanan, kartu
  "Beri Testimoni" di dashboard, dan link "Testimoni" di navbar landing.

### Perubahan yang dilakukan

Skup yang dikerjakan: **hanya tombol rating pada kartu pesanan berstatus `selesai`**
di halaman Riwayat Pesanan (tombol saja, tanpa tambahan toast/redirect baru).

| File | Baris | Detail |
|---|---|---|
| `resources/views/dashboard/customer/pesanan/index.blade.php` | 157–167 | Blok aksi kanan bawah dipecah. **Sebelumnya:** `@elseif($isSelesai \|\| $isDitolak)` → hanya tombol "Pesan Lagi". **Sekarang:** `$isSelesai` → tombol **"Beri/Ubah Rating"** (ikon bintang `ph-star`, aksen oranye `#f2994a`, link ke `route('pesanan.rating.form', $pesanan->id_pesanan)`) + tombol "Pesan Lagi"; `$isDitolak` → tetap "Pesan Lagi" saja |

Aman karena `RatingController@form` sudah memvalidasi sendiri (403 jika bukan pesanan
milik user / status bukan `selesai`).

### Yang BELUM dikerjakan dari titik masuk (pekerjaan lanjutan)

| Task | File | Detail |
|---|---|---|
| Tombol rating di detail pesanan | `resources/views/dashboard/customer/pesanan/show.blade.php` | Di blok status `selesai` tambahkan tombol ke form rating (pola "Unduh Invoice PDF") |
| Kartu "Beri Testimoni" (Alur 2) | `resources/views/dashboard/customer/dashboard/index.blade.php` | Link ke `rating.buat` |
| Link "Testimoni" di navbar landing | `resources/views/layouts/tampilan_utama.blade.php` + `app/Helpers/StaticContent.php` | Tambah `NAV_TESTIMONI`, masuk daftar `$is_frontend`, link navbar desktop & mobile |

---

## 2. REDIRECT SETELAH SUBMIT RATING → BERANDA USER — ✅ SUDAH DIEKSEKUSI

### Bug/perilaku lama

Setelah customer submit rating (Alur 1), sistem membawa user ke **halaman verifikasi
pembayaran** (`pesanan.show`). Permintaan: setelah rating selesai, arahkan ke **beranda
dashboard user yang sudah login** saja.

### Perubahan yang dilakukan

| File | Baris | Detail |
|---|---|---|
| `app/Http/Controllers/RatingController.php` | 83–85 | `store()` Alur 1: `redirect()->route('pesanan.show', ...)` → `redirect()->route('dashboard')`. Toast tetap dipertahankan |

`route('dashboard')` sudah terdaftar di `routes/web.php` (grup auth+verified + role
admin|user) → `/dashboard` (beranda user login). **Alur 2 (`storeLayanan`) juga
disesuaikan** — tadinya kembali ke `rating.layanan.form`, sekarang ikut ke dashboard:

| File | Baris | Detail |
|---|---|---|
| `app/Http/Controllers/RatingController.php` | 88–89 | `store()` Alur 1: redirect → `route('dashboard')` |
| `app/Http/Controllers/RatingController.php` | 136–138 | `storeLayanan()` Alur 2: redirect → `route('dashboard')` |
| `tests/Feature/RatingFeatureTest.php` | 109, 129, 135, 239, 244 | Asert Alur 1: `pesanan.show` → `route('dashboard')` |
| `tests/Feature/RatingFeatureTest.php` | 192, 210, 215 | Asert Alur 2 sukses: tambah `assertRedirect(route('dashboard'))` |

---

## 3. KLARIFIKASI: `id_pesanan` NULL BUKAN `id_layanan` — ✅ TIDAK ADA PERUBAHAN

### Temuan

Awalnya dikira ada rating tersimpan dengan `id_layanan` NULL. Penelusuran (kode + query
DB langsung `wrapping_db.ratings`) membuktikan:

- **Tidak ada** baris dengan `id_layanan` NULL (0 dari 2). `id_layanan` adalah FK
  **non-nullable** (`constrained`) dan validasi `required|exists:layanans,id_layanan`
  memastikan selalu terisi. Juga data aktual terisi benar (Audio Mobil / Variasi Mobil).
- Yang NULL justru **`id_pesanan`** pada rating **Alur 2** (rating tanpa pesanan via
  dropdown layanan) — ini **perilaku yang disengaja**, bukan bug.

| Konfirmasi | Nilai |
|---|---|
| Kolom `id_layanan` | FK non-nullable → selalu terisi |
| Kolom `id_pesanan` | Nullable → NULL hanya untuk Alur 2 (rating tanpa pesanan) |
| Unique Alur 2 | `(id_user, id_layanan, order_ref)` dengan `order_ref = 0` saat `id_pesanan` NULL |

### Keputusan

**Tidak perlu perbaikan.** Skema, relasi, dan Alur 2 sudah konsisten dengan rencana awal
(dropdown layanan, `id_pesanan` boleh kosong, `id_layanan` selalu terisi).

---

## 4. DISKUSI: TAMPILAN RATING DI KARTU LAYANAN — SEPAKAT TUGAS FRONTEND

### Analisis

- **Data sudah lengkap di backend**: `TestimoniController::dataCache()` menghasilkan list
  ulasan user + `summary` (`average`, `total`, `distribution`, `per_layanan` berisi
  `avg` + `count` per layanan), di-cache dengan kunci `testimoni_ratings`.
- Halaman `/testimoni` **sudah** memakai data itu (rata-rata + list ulasan + top 3 layanan).
- **Kartu layanan di katalog/dashboard BELUM** memakai data tersebut: tidak ada blok
  bintang/rata-rata/ulasan, dan controller yang merender kartu
  (`CustomerController@katalog`, `DashboardController@layanan`) **tidak mengoper**
  `$summary` ke view.

### Kesepakatan

Menampilkan **rata-rata + beberapa user yang sudah rating pada kartu layanan** adalah
**tugas frontend** (render bintang, angka, thumbnail user). Syarat prasyarat backend:
controller kartu perlu mengoper `$summary` (atau `per_layanan`) dari
`TestimoniController::dataCache()` ke view — titik tempel kecil ini belum dikerjakan.

---

## 5. BALASAN RATING OLEH ADMIN — ⏸️ DITUNDAK (keep saja)

### Analisis

Kebutuhan admin: *"Sebagai Admin, saya bisa melihat dan membalas rating yang diberikan
customer."*

| Kemampuan | Status |
|---|---|
| Melihat rating | ✅ Sudah ada & lengkap (`RatingResource` Filament: kolom user, tipe, layanan, kode pesanan, bintang, ulasan, foto, toggle `is_tampil`, filter, hapus, halaman detail) |
| Membalas rating | ❌ Belum ada sama sekali (tidak ada kolom balasan di tabel `ratings`, model, maupun Filament; `canEdit = false`) |

### Keputusan

**Dikep (ditunda).** Jika nanti dieksekusi, butuh (dari nol, bukan hanya frontend):
1. Migration baru: kolom `balasan_admin` (text, nullable) + `dibalas_at` (timestamp).
2. Model `Rating`: tambah ke `$fillable`.
3. Filament: `canEdit = true` + halaman edit berisi textarea balasan.
4. Opsional: tampilkan balasan di halaman testimoni publik.

---

## 6. ANALISIS KEAMANAN & VALIDASI RATING — ✅ SUDAH BAIK, ADA BEKERJAAN KECIL

### Sudah terimplementasi (baik)

| Aspek | Detail |
|---|---|
| Auth | Web: `auth` + `verified`. API: `auth:sanctum` |
| Ownership (IDOR) | Alur 1: `where('id_user', Auth::id())`; API juga cek `id_user` |
| Syarat status | Rating hanya jika `status === selesai` (web & API → 403) |
| Validasi input | `id_layanan` required+exists, `rating` int 1–5, `ulasan` ≤500, foto maks 2 (jpg/jpeg/png/webp ≤5MB) — web & API konsisten |
| Mass assignment | Service membangun array create eksplisit; `is_tampil` tidak bisa dimanipulasi user |
| Rate limit | Web `throttle:60,5`; API `throttle:api` 60/menit per user/ip |
| CSRF | Web via middleware Laravel; API via token Sanctum |
| XSS/Injection | Tanpa raw SQL; output Blade di-escape; Filament aman |
| Cache | Invalidasi otomatis `testimoni_ratings` saat saved/deleted |
| Notifikasi | `NotifyAdminRating` defensif (try/catch) → tidak memblokir simpan |

### Kelemahan yang ditemukan (belum dikerjakan)

| # | Kelemahan | Detail |
|---|---|---|
| 1 | Race condition submit ganda → HTTP 500 | `storeForLayanan`/`storeForPesanan` pakai *check-then-insert* non-atomik; `QueryException` dari unique constraint tidak ditangkap. Prioritas tertinggi, murah diperbaiki |
| 2 | Cek tipe file foto percaya ekstensi client | `assertValidPhoto` hanya cek `getClientOriginalExtension()`, bukan MIME asli (`getMimeType()`) |
| 3 | `RatingPolicy` tidak terpakai (dead code) | Kontroller/API pakai cek manual, bukan `$this->authorize()`; policy tidak konsisten |
| 4 | API rating tanpa `verified` | `auth:sanctum` saja → user belum verifikasi email tetap bisa rating via API |

---

## 7. RENCANA DISEPAKATI: FILTER KONTEN TIDAK PANTAS PADA ULASAN — 📋 BELUM DIEKSEKUSI

### Latar belakang

Saat ini **tidak ada filter konten sama sekali** pada ulasan — validasi hanya
`nullable|string|max:500`. Pengaman satu-satunya adalah manual: admin toggle `is_tampil`
atau hapus rating via Filament.

### Keputusan yang disepakati (12 pertanyaan desain)

| Aspek | Keputusan |
|---|---|
| Package sumber daftar kata | **`heyitsmi/content-guard`** (Laravel-ready, kamus Indonesia, smart-regex, zero deps) |
| Strategi | **Tanpa tolak submit** — rating tetap tersimpan, tapi terdeteksi kotor → `is_tampil = false` + notif admin |
| Logika berlapis | Semua kata terdeteksi → auto-hide + notif admin (tidak ada penolakan keras) |
| Deteksi variasi | Package content-guard memakai smart-regex (deteksi leet-speak spt `b4j1ng`, `s.l.o.t`); tidak memakai exact-match murni |

### Alur outcome yang disepakati

```
Submit rating/ulasan → ContentGuard::hasBadWords(ulasan)
  ├─ Bersih → is_tampil = true  → tampil publik + notif admin (seperti biasa)
  └─ Kotor  → is_tampil = false → tidak tampil publik + notif admin → admin review & toggle di Filament
```

### Rencana eksekusi

| # | Langkah | Detail |
|---|---|---|
| 1 | Install package | `composer require heyitsmi/content-guard`; `php artisan vendor:publish --tag="content-guard-config"` (opsional) |
| 2 | Integrasi `RatingService` | `app/Services/RatingService.php` — helper `cekKataKotor()` → `ContentGuard::hasBadWords()`; di `validateAndCreate` set `is_tampil = !kotor`; di `update` set `is_tampil = false` saat kotor. Otomatis mencakup Alur 1 + Alur 2 + web + API (semua lewat service) |
| 3 | Notifikasi admin | `app/Listeners/NotifyAdminRating.php` — tambah penanda "🌀 Menunggu moderasi" saat `is_tampil=false` |
| 4 | Filament | Opsional badge "Perlu Moderasi" di `RatingsTable`; toggle `is_tampil` sudah tersedia |
| 5 | Test | `tests/Feature/RatingFeatureTest.php` — ulasan kotor → `is_tampil=false`; ulasan bersih → `is_tampil=true` |

### Catatan/batasan

- Karena strategi "tanpa tolak", customer tetap diarahkan ke dashboard dengan toast sukses;
  hanya konten yang tidak tampil publik.
- Smart-regex berpotensi *false-positive* kecil → admin tetap bebas toggle `is_tampil`.
- Penyesuaian daftar kata via config/custom dictionary package (tanpa ubah kode).

---

# Ringkasan Status Per Sesi (8 September 2026)

| Item | Status |
|---|---|
| Tombol "Beri/Ubah Rating" di riwayat pesanan (`selesai`) | ✅ Dieksekusi |
| Redirect submit rating → dashboard `route('dashboard')` | ✅ Dieksekusi |
| `id_pesanan` NULL pada Alur 2 = perilaku wajar | ✅ Konfirmasi, tanpa perubahan |
| Tampilan rata-rata rating di kartu layanan | 📋 Tugas frontend (perlu controller oper `$summary`) |
| Balasan rating admin | ⏸️ Ditunda (keep) |
| Perbaikan race condition submit ganda (HTTP 500) | 📋 Rencana, belum dikerjakan |
| Validasi MIME asli foto rating | 📋 Rencana, belum dikerjakan |
| `RatingPolicy` dead code | 📋 Rencana, belum dikerjakan |
| `verified` di API rating | 📋 Rencana, belum dikerjakan |
| Filter konten tidak pantas (content-guard) | 📋 Rencana disepakati, belum dikerjakan |

---

Akses

   Host : 38.103.171.82
   port : 22
   Username : developer
   Password : developer123

---

# Fitur Rating — Daftar File yang Dibuat & Diubah (September 2026)

Bagian ini mendata semua file yang terlibat dalam pembuatan fitur rating/testimoni.
Tujuannya supaya mudah dicari: file mana yang dibuat baru, mana yang hanya diubah,
dan masing-masing gunanya buat apa. Semua ditulis dengan bahasa sederhana.

> Fitur rating memungkinkan pelanggan memberi **bintang 1–5 + ulasan + foto (maks. 2)**.
> Ada 2 cara: **via pesanan yang selesai** (pelanggan baru) dan **via pilihan layanan**
> (pelanggan lama). Hasilnya tampil di **halaman Testimoni** dan bisa dikelola di **admin**.

> ⚠️ **UPDATE:** Sejak sesi **"Nonaktifkan Alur 2 Rating"** di akhir dokumen ini,
> hanya **1 alur yang aktif** — rating **via pesanan berstatus `selesai`**.
> Alur 2 (dropdown layanan tanpa pesanan) telah **dinonaktifkan**.

---

## A. File Baru (dibuat khusus untuk fitur rating)

### 1. Database (4 file)

| File | Fungsinya |
|---|---|
| `database/migrations/2026_09_07_171200_create_ratings_table.php` | Membuat tabel `ratings` — tempat simpan rating (bintang, ulasan, status tampil) + kunci unik supaya pelanggan tidak bisa rating dobel |
| `database/migrations/2026_09_07_171300_create_rating_medias_table.php` | Membuat tabel `rating_medias` — tempat daftar foto tiap rating |
| `database/migrations/2026_09_17_000001_add_balasan_admin_to_ratings_table.php` | Menambah kolom "balasan admin" dan waktu balasan di tabel rating |
| `database/migrations/2026_09_17_000010_drop_testimonis_table.php` | Membuang tabel `testimonis` yang lama (sudah digantikan tabel `ratings`) |

### 2. Model (2 file)

| File | Fungsinya |
|---|---|
| `app/Models/Rating.php` | Model utama rating. Berisi relasi ke user, pesanan, layanan, dan foto. Otomatis membersihkan cache daftar testimoni setiap ada rating baru/diubah/dihapus |
| `app/Models/RatingMedia.php` | Model untuk tiap foto rating |

### 3. Logika utama (1 file)

| File | Fungsinya |
|---|---|
| `app/Services/RatingService.php` | "Otak" dari semua aturan rating: menyimpan rating baru, mengubah, menghapus, memvalidasi (bintang 1–5, ulasan maks. 500 huruf, foto maks. 2 file jpg/jpeg/png/webp maks. 5MB), menyimpan file foto, dan menangani submit dobel |

### 4. Event & notifikasi (3 file)

| File | Fungsinya |
|---|---|
| `app/Events/RatingCreated.php` | Tanda "ada rating baru" yang dikirim sistem |
| `app/Events/RatingUpdated.php` | Tanda "ada rating yang diubah" yang dikirim sistem |
| `app/Listeners/NotifyAdminRating.php` | Menerima tanda di atas lalu memberi tahu semua admin (notifikasi di situs + di panel admin). Dibuat aman supaya kegagalan notifikasi tidak menggagalkan penyimpanan rating |

### 5. Keamanan (1 file)

| File | Fungsinya |
|---|---|
| `app/Policies/RatingPolicy.php` | Aturan "siapa boleh rating": hanya pemilik pesanan dan pesanan harus berstatus selesai |

### 6. Halaman web (2 file)

| File | Fungsinya |
|---|---|
| `app/Http/Controllers/RatingController.php` | Mengatur tampilan form rating dan proses simpan, untuk 2 alur (via pesanan & via pilihan layanan) |
| `app/Http/Controllers/TestimoniController.php` | Menyediakan data & halaman `Testimoni` publik (`/testimoni`): rata-rata bintang, filter, dan urutan ulasan |

### 7. API (2 file)

| File | Fungsinya |
|---|---|
| `app/Http/Controllers/Api/RatingController.php` | Versi API dari fitur rating: daftar rating publik, simpan rating, dan tampilkan "rating punya saya" |
| `app/Http/Middleware/EnsureApiEmailVerified.php` | Aturan untuk API: user harus sudah verifikasi email sebelum boleh memberi rating |

### 8. Panel admin (5 file)

| File | Fungsinya |
|---|---|
| `app/Filament/Resources/Ratings/RatingResource.php` | Membuat menu "Rating & Testimoni" di panel admin |
| `app/Filament/Resources/Ratings/Tables/RatingsTable.php` | Isi daftar rating di admin (user, layanan, bintang, ulasan, jumlah foto, tombol tampil/sembunyi, hapus) |
| `app/Filament/Resources/Ratings/Pages/ListRatings.php` | Halaman daftar rating di admin |
| `app/Filament/Resources/Ratings/Pages/ViewRating.php` | Halaman detail satu rating di admin |
| `app/Filament/Widgets/RecentRatingsWidget.php` | Kotak "Rating & Testimoni Terbaru" di dashboard admin |

### 9. Tampilan pengguna (4 file)

| File | Fungsinya |
|---|---|
| `resources/views/dashboard/customer/pesanan/rating.blade.php` | Form rating via pesanan (bintang, ulasan, upload foto) |
| `resources/views/dashboard/customer/rating/layanan.blade.php` | Form rating via pilihan layanan (untuk pelanggan lama) |
| `resources/views/landing/testimoni/index.blade.php` | Halaman Testimoni publik (rata-rata bintang, filter, daftar kartu ulasan + foto) |
| `resources/views/dashboard/customer/dashboard/_testimonial-cta.blade.php` | Kartu ajakan "Tulis Ulasan" di dashboard customer |

### 10. Uji coba & paket (2 file)

| File | Fungsinya |
|---|---|
| `tests/Feature/RatingFeatureTest.php` | 12 pengujian otomatis untuk alur rating 1 & 2, dan halaman testimoni publik |
| `config/content-guard.php` | Pengaturan paket penyaring kata tidak pantas pada ulasan |

---

## B. File Lama yang Diubah (disiapkan agar fitur rating berjalan)

| File | Perubahannya |
|---|---|
| `routes/web.php` | Menambah alamat halaman: `/testimoni`, `/pesanan/{id}/rating`, `/rating/buat` |
| `routes/api.php` | Menambah alamat API: daftar rating publik, simpan rating, rating "saya" |
| `bootstrap/app.php` | Mendaftarkan middleware `api.verified` supaya bisa dipakai di API |
| `app/Models/Pesanan.php` | Menambah relasi `rating()` (satu pesanan boleh punya rating) |
| `app/Models/Layanan.php` | Menambah relasi `ratings()` (satu layanan bisa punya banyak rating) |
| `app/Providers/AppServiceProvider.php` | Mendaftarkan `RatingService` supaya bisa dipakai di semua halaman |
| `app/Providers/AuthServiceProvider.php` | Mendaftarkan aturan `RatingPolicy` |
| `app/Providers/EventServiceProvider.php` | Menghubungkan event rating → notifikasi admin |
| `app/Services/CacheService.php` | Menambah kunci cache `testimoni_ratings` supaya bisa dibersihkan |
| `app/Helpers/StaticContent.php` | Menambah teks menu "Testimoni" |
| `resources/views/dashboard/customer/pesanan/index.blade.php` | Menambah tombol "Beri/Ubah Rating" pada pesanan berstatus selesai |
| `resources/views/dashboard/customer/pesanan/show.blade.php` | Menambah tombol menuju form rating di detail pesanan selesai |
| `resources/views/dashboard/customer/dashboard/index.blade.php` | Memasang kartu ajakan "Tulis Ulasan" |
| `resources/views/layouts/tampilan_utama.blade.php` | Menambah link menu "Testimoni" di navbar |
| `composer.json` & `composer.lock` | Memasang paket `heyitsmi/content-guard` (penyaring kata tidak pantas) |
| `app/Models/Testimoni.php` (dihapus) | Model lama sudah tidak terpakai karena tabel `testimonis` dibuang |

---

# Sesi Nonaktifkan Alur 2 Rating — Dokumentasi Perubahan (September 2026)

> **Status:** ✅ **SELESAI — Alur 2 (rating via dropdown layanan, tanpa pesanan) dinonaktifkan.**
> **Metode:** non-destruktif via **komentar** — seluruh kode tetap disimpan sebagai
> referensi dan bisa di-re-enable kapan saja dengan menghapus tanda komentar.

---

## 1. Latar Belakang & Keputusan

- Alur 2 adalah rating yang diberikan pelanggan **tanpa data pesanan** (cukup daftar
  akun lalu pilih layanan dari dropdown). Saat sistem dipakai secara nyata, alur ini
  dinilai **tidak relevan**, sehingga diputuskan untuk **dinonaktifkan**.
- Keputusan akhir: rating hanya boleh diberikan melalui **Alur 1 — pesanan berstatus
  `selesai`** (customer memberi rating setelah melakukan pemesanan).
- Rating Alur 2 yang **sudah ada sebelumnya** di database (`id_pesanan` = NULL)
  **tidak dihapus** dan **tetap tampil** di halaman `/testimoni` dan `GET /api/rating`.

## 2. Perubahan yang Dilakukan (per File)

| File | Perubahan |
|---|---|
| `routes/web.php` | Rute `GET /rating/buat` (`rating.layanan.form`) dan `POST /rating/buat` (`rating.layanan.store`) dikomentari `[DISABLED]` |
| `routes/api.php` | Endpoint `POST /api/rating/layanan` (`storeByLayanan`) dikomentari `[DISABLED]` |
| `resources/views/dashboard/customer/dashboard/index.blade.php` | Include kartu CTA `_testimonial-cta` dimatikan (komentar Blade) |
| `resources/views/landing/testimoni/index.blade.php` | 2 tombol "Tulis Ulasan / Beri Rating" (filter atas + empty-state) dikomentari (Blade comment) |
| `app/Http/Controllers/RatingController.php` | Method `formLayanan()` & `storeLayanan()` diberi penanda `[DISABLED]` (tidak terpanggil oleh rute mana pun) |
| `app/Http/Controllers/Api/RatingController.php` | Method `storeByLayanan()` diberi penanda `[DISABLED]` |
| `app/Services/RatingService.php` | Method `storeForLayanan()` diberi penanda `[DISABLED]` |
| `tests/Feature/RatingFeatureTest.php` | Blok `describe('Alur 2 ...')` dikomentari; test "user yang sama boleh rate layanan yang sama di pesanan berbeda" dipindah ke describe Alur 1; test "ringkasan rata-rata" memakai `Rating::create` langsung (bukan route) |

## 3. Behavior Sebelum / Sesudah

| Aspek | Sebelum | Sesudah |
|---|---|---|
| Jalur input rating | 2 alur (via pesanan + via dropdown layanan) | **1 alur** (via pesanan berstatus `selesai`) |
| URL `/rating/buat` (web) | Aktif | Tidak aktif (rute dihapus via komentar → 404) |
| `POST /api/rating/layanan` | Aktif | Tidak aktif |
| Kartu CTA "Tulis Ulasan" di dashboard | Tampil | Tidak dirender |
| Tombol "Tulis Ulasan" di `/testimoni` | Tampil | Tidak dirender |
| Data lama `id_pesanan` NULL | Tampil publik | **Tetap tampil** (tidak dihapus) |
| Tombol "Beri/Ubah Rating" di pesanan selesai | Aktif | **Tetap aktif** (tidak disentuh) |

## 4. Hal yang TIDAK Diubah

- **Database:** kolom `order_ref`, unique index `('id_user','id_layanan','order_ref')`,
  dan semua migrasi tetap ada (tidak ada migrasi baru).
- **Tampilan & endpoint publik:** `/testimoni`, `GET /api/rating`, `GET /api/rating/saya`
  tetap aktif; data lama Alur 2 tetap tampil.
- **File referensi:** `resources/views/dashboard/customer/rating/layanan.blade.php`
  dan `resources/views/dashboard/customer/dashboard/_testimonial-cta.blade.php`
  dipertahankan (tidak dirender) — berisi referensi `route('rating.layanan.*')`
  yang aman karena hanya ada di dalam komentar/file yang tidak dipakai.
- **Settings:** `cta_rating` (dipakai tombol Alur 1 di `pesanan/index` & `show`),
  `testimoni_cta_title`, `testimoni_cta_desc`.
- **Lainnya:** navbar menu Testimoni, `RatingPolicy`, event/listener `NotifyAdminRating`,
  panel admin (Filament) `RatingResource`.

## 5. Verifikasi

- `php artisan route:list` — tidak ditemukan lagi rute `rating/buat` (web) maupun
  `api/rating/layanan` (API); rute Alur 1 (`pesanan/{id}/rating` GET/POST,
  `api/pesanan/{pesanan}/rating`) tetap ada.
- `php artisan view:clear` — cache view dibersihkan.
- `php artisan test --filter=RatingFeatureTest` — **9 passed (33 assertions)**.
  Sebelumnya 12 test; berkurang karena block Alur 2 dikomentari.
- Referensi `route('rating.layanan.*')` yang tersisa hanya berada di dalam komentar
  atau file Blade yang tidak dirender.

## 6. Cara Re-enable (jika diinginkan nanti)

1. `routes/web.php` — hapus komentar pada grup `Route::prefix('rating')` (2 rute).
2. `routes/api.php` — hapus komentar pada `Route::post('/rating/layanan', ...)`.
3. `resources/views/dashboard/customer/dashboard/index.blade.php` — aktifkan kembali
   `@include('dashboard.customer.dashboard._testimonial-cta')`.
4. `resources/views/landing/testimoni/index.blade.php` — hapus komentar Blade pada
   2 tombol "Tulis Ulasan / Beri Rating".
5. `tests/Feature/RatingFeatureTest.php` — hapus komentar block `describe('Alur 2 ...')`.
6. Jalankan ulang `php artisan route:list` dan `php artisan test --filter=RatingFeatureTest`.
