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