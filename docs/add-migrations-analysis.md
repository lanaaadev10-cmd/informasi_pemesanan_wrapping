# Analisis Migration `add_*`

Laporan ini memetakan setiap file migration `add_*` ke tabel dan kolom yang ditambahkan, serta file kode yang memanggil kolom tersebut. Gunakan file ini untuk menilai dampak penghapusan atau perubahan.

> Catatan: menghapus file migration tidak menghapus kolom di DB saat ini. Hapus hanya setelah rollback yang aman.

---

## Ringkasan per migration (file -> tabel -> kolom -> lokasi pemanggilan)

- [database/migrations/2026_05_25_094309_add_layanan_galeri_hero_images_to_profil_perusahaans_table.php](database/migrations/2026_05_25_094309_add_layanan_galeri_hero_images_to_profil_perusahaans_table.php)
  - Tabel: `profil_perusahaans`
  - Kolom: `layanan_hero_image`, `galeri_hero_image`
  - Dipanggil di: [app/Models/ProfilPerusahaan.php](app/Models/ProfilPerusahaan.php), Filament resources under [app/Filament/Resources](app/Filament/Resources)

- [database/migrations/2026_05_22_070936_add_performance_indexes.php](database/migrations/2026_05_22_070936_add_performance_indexes.php)
  - Tabel: `pesanans`, `keranjangs`, `detail_keranjangs`, `pembayarans`, `notifikasis`
  - Perubahan: index dan unique constraint (digunakan untuk optimasi query pada daftar pesanan/keranjang/notifikasi)
  - Dipanggil/berguna untuk: controllers/API query (mis. order listing), file-file di [app/Http/Controllers](app/Http/Controllers)

- [database/migrations/2026_05_21_230000_add_all_pages_cms_fields_to_profil_perusahaans_table.php](database/migrations/2026_05_21_230000_add_all_pages_cms_fields_to_profil_perusahaans_table.php)
  - Tabel: `profil_perusahaans`
  - Kolom: banyak field CMS (mis. `katalog_hero_title`, `katalog_hero_desc`, `pesanan_page_title_all`, `keranjang_hero_text`, `checkout_step_1_label`, `dashboard_quick_services_title`, dll.)
  - Dipanggil di: [app/Models/ProfilPerusahaan.php](app/Models/ProfilPerusahaan.php), [app/Models/Traits/HasCompanyCms.php](app/Models/Traits/HasCompanyCms.php), controllers seperti [app/Http/Controllers/DashboardController.php](app/Http/Controllers/DashboardController.php), serta Filament resources

- [database/migrations/2026_05_21_220000_add_layout_config_to_profil_perusahaans_table.php](database/migrations/2026_05_21_220000_add_layout_config_to_profil_perusahaans_table.php)
  - Tabel: `profil_perusahaans`
  - Kolom: `tentang_kami_values_columns`, `tentang_kami_show_values`, `tentang_kami_show_history`, `tentang_kami_show_team`, `layanan_grid_columns`, `layanan_card_style`, `layanan_show_benefits`, `layanan_show_warranty`, `accent_color`, `primary_layout`, `dark_mode`
  - Dipanggil di: model/trait `ProfilPerusahaan`/`HasCompanyCms`, Filament settings, front-end views (layout/dark-mode handling)

- [database/migrations/2026_05_21_174137_add_tentang_kami_hero_image_to_profil_perusahaans_table.php](database/migrations/2026_05_21_174137_add_tentang_kami_hero_image_to_profil_perusahaans_table.php)
  - Tabel: `profil_perusahaans`
  - Kolom: `tentang_kami_hero_image`
  - Dipanggil di: [app/Filament/Resources/TentangKamiResource.php](app/Filament/Resources/TentangKamiResource.php), [app/Models/ProfilPerusahaan.php](app/Models/ProfilPerusahaan.php), About page views

- [database/migrations/2026_05_21_160000_add_tentang_kami_and_layanan_to_profil_perusahaans_table.php](database/migrations/2026_05_21_160000_add_tentang_kami_and_layanan_to_profil_perusahaans_table.php)
  - Tabel: `profil_perusahaans`
  - Kolom: `tentang_kami_hero_title`, `tentang_kami_hero_desc`, `tentang_kami_team_members` (json), `layanan_hero_title`, `layanan_hero_desc`, `layanan_1_*` .. `layanan_4_*` termasuk `layanan_*_fitur` (json), `layanan_garansi_title`, `layanan_cta_title`, dll.
  - Dipanggil di: [app/Models/ProfilPerusahaan.php](app/Models/ProfilPerusahaan.php) (casts untuk json), Filament Halaman Layanan resource, front controllers/views

- [database/migrations/2026_05_21_134634_add_customer_dashboard_fields_to_profil_perusahaans_table.php](database/migrations/2026_05_21_134634_add_customer_dashboard_fields_to_profil_perusahaans_table.php)
  - Tabel: `profil_perusahaans`
  - Kolom: `dashboard_member_title`, `dashboard_member_desc`, `dashboard_member_progress`, `dashboard_member_benefits`, `dashboard_service_{1..4}_title`, `dashboard_service_{1..4}_desc`, `dashboard_service_{1..4}_icon`, `dashboard_empty_title`, `dashboard_empty_desc`
  - Dipanggil di: [app/Http/Controllers/DashboardController.php](app/Http/Controllers/DashboardController.php), Filament dashboard resources, dashboard views

- [database/migrations/2026_05_18_115609_add_estimasi_waktu_to_layanans_table.php](database/migrations/2026_05_18_115609_add_estimasi_waktu_to_layanans_table.php)
  - Tabel: `layanans`
  - Kolom: `estimasi_waktu`
  - Dipanggil di: [app/Models/Layanan.php](app/Models/Layanan.php), Filament `LayanansTable`/form, API controllers under [app/Http/Controllers/Api](app/Http/Controllers/Api)

- [database/migrations/2026_05_18_114013_add_jadwal_and_kendaraan_fields_to_form_pesanans_table.php](database/migrations/2026_05_18_114013_add_jadwal_and_kendaraan_fields_to_form_pesanans_table.php)
  - Tabel: `form_pesanans`
  - Kolom: `model_kendaraan`, `warna_kendaraan`, `nomor_polisi`, `tahun_produksi`, `lokasi_pengerjaan`, `jadwal_pengerjaan`, `estimasi_durasi`
  - Dipanggil di: [app/Models/FormPesanan.php](app/Models/FormPesanan.php), [app/Http/Controllers/PesananController.php](app/Http/Controllers/PesananController.php), Filament Pesanan forms

- [database/migrations/2026_05_18_112200_add_beranda_fields_to_profil_perusahaans_table.php](database/migrations/2026_05_18_112200_add_beranda_fields_to_profil_perusahaans_table.php)
  - Tabel: `profil_perusahaans`
  - Kolom: `home_title`, `home_subtitle`, `home_hero_image`, `home_feature_title`, `home_feature_subtitle`, login/register CMS fields
  - Dipanggil di: `HasCompanyCms` trait [app/Models/Traits/HasCompanyCms.php](app/Models/Traits/HasCompanyCms.php), home controllers/views, Filament

- [database/migrations/2026_05_14_194354_add_auth_extra_cms_to_profil_perusahaans_table.php](database/migrations/2026_05_14_194354_add_auth_extra_cms_to_profil_perusahaans_table.php)
  - Tabel: `profil_perusahaans`
  - Kolom: `auth_badge`, `footer_copyright`
  - Dipanggil di: footer views and auth-related Filament settings

- [database/migrations/2026_05_14_192434_add_home_more_cms_to_profil_perusahaans_table.php](database/migrations/2026_05_14_192434_add_home_more_cms_to_profil_perusahaans_table.php)
  - Tabel: `profil_perusahaans`
  - Kolom: `home_prof_title`, `home_prof_subtitle`, `home_catalog_title`, `home_catalog_subtitle`, `home_recent_title`, `home_recent_subtitle`, `home_cta_title`, `home_cta_subtitle`
  - Dipanggil di: home views and Filament CMS

- [database/migrations/2026_05_14_192157_add_about_cms_to_profil_perusahaans_table.php](database/migrations/2026_05_14_192157_add_about_cms_to_profil_perusahaans_table.php)
  - Tabel: `profil_perusahaans`
  - Kolom: `about_hero_title`, `about_hero_subtitle`, `stats_experience`, `about_feature_*`, `about_feature_list` (json), social links, `meta_title`, `meta_description`
  - Dipanggil di: About page controllers/views and Filament resources

- [database/migrations/2026_05_14_191247_add_testimonis_json_to_profil_perusahaans_table.php](database/migrations/2026_05_14_191247_add_testimonis_json_to_profil_perusahaans_table.php)
  - Tabel: `profil_perusahaans`
  - Kolom: `testimonis_json` (json)
  - Dipanggil di: [app/Http/Controllers/DashboardController.php](app/Http/Controllers/DashboardController.php), HasCompanyCms trait, testimonials view

- [database/migrations/2026_05_14_190133_add_katalog_galeri_cms_to_profil_perusahaans_table.php](database/migrations/2026_05_14_190133_add_katalog_galeri_cms_to_profil_perusahaans_table.php)
  - Tabel: `profil_perusahaans`
  - Kolom: `katalog_title`, `katalog_subtitle`, `galeri_title`, `galeri_subtitle`
  - Dipanggil di: katalog/galeri views and model

- [database/migrations/2026_05_14_184720_add_dashboard_and_steps_to_profil_perusahaans_table.php](database/migrations/2026_05_14_184720_add_dashboard_and_steps_to_profil_perusahaans_table.php)
  - Tabel: `profil_perusahaans`
  - Kolom: `dashboard_title`, `dashboard_subtitle`, `step_1_title`..`step_4_desc`
  - Dipanggil di: ordering UI, dashboard views

- [database/migrations/2026_05_13_012201_add_about_fields_to_profil_perusahaans_table.php](database/migrations/2026_05_13_012201_add_about_fields_to_profil_perusahaans_table.php)
  - Tabel: `profil_perusahaans`
  - Kolom: `visi`, `misi`, `sejarah`
  - Dipanggil di: About page

- [database/migrations/2026_05_12_235243_add_cms_fields_to_profil_perusahaans_table.php](database/migrations/2026_05_12_235243_add_cms_fields_to_profil_perusahaans_table.php)
  - Tabel: `profil_perusahaans`
  - Kolom: home statistics, keunggulan cards, home steps (mis. `home_stat1_value`, `home_keunggulan_card1_title`, `home_step1_title`, ...)
  - Dipanggil di: home views and `HasCompanyCms`

- [database/migrations/2026_05_11_184320_add_foto_contoh_to_layanans_table.php](database/migrations/2026_05_11_184320_add_foto_contoh_to_layanans_table.php)
  - Tabel: `layanans`
  - Kolom: `foto_contoh`
  - Dipanggil di: [app/Http/Resources/LayananResource.php](app/Http/Resources/LayananResource.php), [app/Models/Layanan.php](app/Models/Layanan.php), Filament `LayananForm`

- [database/migrations/2026_05_11_182408_add_kategori_to_layanans_table.php](database/migrations/2026_05_11_182408_add_kategori_to_layanans_table.php)
  - Tabel: `layanans`
  - Kolom: `kategori`
  - Dipanggil di: API controllers ([app/Http/Controllers/Api/LayananController.php](app/Http/Controllers/Api/LayananController.php)), Filament forms, seeders, front-end filter endpoints

- [database/migrations/2026_05_11_181853_add_tipe_paket_to_layanans_table.php](database/migrations/2026_05_11_181853_add_tipe_paket_to_layanans_table.php)
  - Tabel: `layanans`
  - Kolom: `tipe_paket`
  - Dipanggil di: API responses, Filament forms

- [database/migrations/2026_05_11_181725_add_cms_fields_to_layanans_table.php](database/migrations/2026_05_11_181725_add_cms_fields_to_layanans_table.php)
  - Tabel: `layanans`
  - Kolom: `fitur` (json)
  - Dipanggil di: `Layanan` model (casts => array), Filament `LayananForm`, API resources

- [database/migrations/2026_05_11_181232_add_details_to_galeris_table.php](database/migrations/2026_05_11_181232_add_details_to_galeris_table.php)
  - Tabel: `galeris`
  - Kolom: `is_featured`, `sub_judul`, `badge_text`
  - Dipanggil di: [app/Models/Galeri.php](app/Models/Galeri.php), Filament Galeri resources, galeri views

- [database/migrations/2026_05_11_175341_add_kategori_to_galeris_table.php](database/migrations/2026_05_11_175341_add_kategori_to_galeris_table.php)
  - Tabel: `galeris`
  - Kolom: `kategori`
  - Dipanggil di: Filament Galeri forms/tables, seeders, galeri filter UI

---

## Rekomendasi: field yang bisa dibuat *statis* vs tetap *dinamis*

Ringkasan prinsip:
- "Transactional" dan data operasi: tetap di database (dinamis). Contoh: `pesanans`, `form_pesanans`, `keranjangs`, `detail_keranjangs`, `pembayarans`, `notifikasis`.
- "Content/CMS/UI text" yang jarang berubah: pertimbangkan diubah menjadi statis (file config / blade / env / translation) jika kamu tidak perlu mengubahnya lewat admin runtime.
- Field yang dipakai untuk filter/fitur fungsional (seperti `kategori` pada `layanans`, `estimasi_waktu`) sebaiknya tetap dinamis karena mempengaruhi query dan data bisnis.

Rekomendasi per-tabel (ringkas):
- profil_perusahaans
  - Buat statis (atau pindahkan ke config/translations/static blade) jika nilai hanya desain/UI dan tidak butuh admin runtime. Contoh kandidat statis: hero titles/descriptions untuk bagian yang jarang berubah, beberapa CTA default.
  - Tetap dinamis: nilai yang admin harus ubah sering (mis. `footer_copyright`, social links jika diubah oleh admin), atau fitur multi-tenant.
  - Alternatif ringan: gabungkan field CMS menjadi satu kolom JSON `cms_content` apabila ingin tetap dinamis tapi menyederhanakan schema.

- layanans
  - Tetap dinamis: `nama_layanan`, `deskripsi`, `harga`, `kategori`, `fitur`, `foto_contoh`, `estimasi_waktu` — ini adalah data produk yang berubah dan dipakai di UI/API.
  - Jangan ubah jadi statis.

- galeris
  - `foto`, `judul`, `kategori`, `is_featured` -> tetap dinamis (konten galerinya seharusnya dapat diubah oleh admin).
  - `badge_text` atau `sub_judul` bisa dipertimbangkan statis jika tidak diedit sering, tapi biasanya admin ingin mengubahnya.

- form_pesanans
  - Seluruh field kendaraan/jadwal harus dinamis (transaksional) tetap di DB.

- Indexes dan constraints (`add_performance_indexes`) -> tetap (jangan hapus). Indeks meningkatkan performa query.

Panduan sederhana untuk migrasi dari dinamis->statis:
1. Pilih field yang ingin dipindah ke statis.
2. Ekspor nilainya dari DB (backup). Simpan default values di file `config/company_cms.php` atau blade partial.
3. Update kode yang membaca kolom tersebut: ubah akses model menjadi config helper `config('company_cms.home_title')` atau panggil helper `companyCms('home_title')`.
4. Uji seluruh view dan halaman admin. Pastikan tidak ada referensi tersisa ke kolom DB.
5. Di environment dev/test: rollback migration yang menambahkan kolom tersebut (atau buat migration untuk drop), lalu hapus file migration dari repo.
6. Commit perubahan dan dokumentasikan perubahan migration history.

Perhatian:
- Jika aplikasi sudah live, jangan langsung drop kolom di production sebelum roll out perubahan code yang membaca config. Lakukan proses bertahap (kode membaca dari config tetapi fallback ke DB sampai migration di-drop).

---

## Langkah aman untuk menghapus migration `add_*` yang ingin kamu buang

1. Di branch/working copy terpisah, buat backup DB dan export tabel `profil_perusahaans` (dan tabel lain jika perlu).
2. Implementasikan perubahan kode yang mengganti pembacaan kolom menjadi sumber statis (`config`/blade). Sertakan fallback ke DB untuk transisi.
3. Jalankan test lokal, periksa views that used the fields.
4. Rollback migration di dev: `php artisan migrate:rollback --step={n}` (hati-hati hitung jumlah langkah).
5. Hapus file migration dari repo dan commit.
6. Di CI / production: jalankan deploy bertahap; pastikan migration history konsisten.

---

## Apakah kamu mau versi CSV atau per-barisan (dengan setiap kolom pemanggil per baris)?
Jika ya, saya bisa men-generate CSV juga di `docs/add-migrations-analysis.csv`.

---

_Terakhir_: file ini disimpan di `docs/add-migrations-analysis.md`.
