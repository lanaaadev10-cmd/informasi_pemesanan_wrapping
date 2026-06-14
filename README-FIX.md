# Dokumentasi Perbaikan Error Sistem

## Ringkasan

Dokumen ini mencatat semua error yang telah ditemukan dan diperbaiki pada sistem Informasi Pemesanan Wrapping. Total **26 error** diidentifikasi, **22 diperbaiki** secara langsung (sisanya bersifat minor/konvensi kode).

---

## 1. Model ProfilPerusahaan (MISSING)

| Item | Detail |
|------|--------|
| **File** | `app/Models/ProfilPerusahaan.php` |
| **Error** | Model tidak ada, direferensi oleh 3 file Filament admin |
| **Dampak** | Filament admin panel crash saat akses Beranda/Tentang Kami |
| **Perbaikan** | Dibuat file model baru dengan `$guarded = ['id']` + casts untuk kolom JSON/boolean/integer |

### Kolom tabel `profil_perusahaans` (dari semua migration):
- Basic: `id`, `nama_perusahaan`, `deskripsi`, `alamat`, `email`, `nomor_telepon`, `logo`, `maps_url`, `timestamps`
- About: `visi`, `misi`, `sejarah`
- Dashboard: `dashboard_title`, `dashboard_subtitle`, `dashboard_member_title`, `dashboard_member_desc`, `dashboard_member_progress`, `dashboard_member_benefits`
- Dashboard Services: `dashboard_service_1_title` s/d `dashboard_service_4_icon`
- Dashboard Empty: `dashboard_empty_title`, `dashboard_empty_desc`
- Steps: `step_1_title` s/d `step_4_desc`
- Beranda: `home_badge_text`, `home_hero_title_line1`, `home_hero_title_line2`, `home_subtitle`, `home_stat1_value`, `home_stat1_label`, `home_stat2_value`, `home_stat2_label`, `home_keunggulan_card1_title` s/d `home_keunggulan_card4_desc`, `home_step1_title` s/d `home_step3_desc`, `home_cta_title`, `home_cta_subtitle`
- Tentang Kami: `tentang_kami_hero_title`, `tentang_kami_hero_desc`, `tentang_kami_hero_image`, `tentang_kami_team_title`, `tentang_kami_team_desc`, `tentang_kami_team_members`, `tentang_kami_show_team`, `tentang_kami_show_values`, `tentang_kami_show_history`, `tentang_kami_values_columns`
- Layanan: `layanan_hero_title`, `layanan_hero_desc`, `layanan_hero_image`, `layanan_1_nama` s/d `layanan_4_gambar`, `layanan_garansi_title`, `layanan_garansi_desc`, `layanan_cta_title`, `layanan_cta_desc`, `layanan_grid_columns`, `layanan_card_style`, `layanan_show_benefits`, `layanan_show_warranty`
- Galeri: `galeri_hero_image`
- Testimoni: `testimonis_json`
- Global: `accent_color`, `primary_layout`, `dark_mode`

---

## 2. Nama Kolom Database Tidak Sesuai

### Masalah Utama
Kode menggunakan nama kolom yang berbeda dari yang didefinisikan di migration.

### Tabel `detail_keranjangs` & `detail_pesanans`

| Kode (SALAH) | Database (BENAR) | File yang Diperbaiki |
|---|---|---|
| `id_layanan` | `id_paket` | `KeranjangService.php`, `PesananService.php`, `AdminDashboardController.php`, `AdminPembayaranController.php` |
| `quantity` | `jumlah` | `KeranjangService.php`, `PesananService.php`, `AdminPembayaranController.php` |
| `custom_data` | (tidak ada) → pakai `catatan_custom` | `KeranjangService.php`, `PesananService.php` |

### Tabel `pembayarans`

| Kode (SALAH) | Database (BENAR) | File yang Diperbaiki |
|---|---|---|
| `status_pembayaran` | `status` | `AdminPembayaranController.php`, `AdminDashboardController.php`, `PembayaranService.php`, `Pembayaran.php` (cast), `PembayaranResource.php` |
| `tanggal_pembayaran` | `tgl_bayar` | `PembayaranService.php`, `PembayaranResource.php` |
| `nomor_referensi` | (tidak ada) | `PembayaranService.php`, `PembayaranResource.php` (dihapus) |

### Tabel `form_pesanans`

| Kode (SALAH) | Database (BENAR) | File yang Diperbaiki |
|---|---|---|
| `kota_pengiriman` | (tidak ada) | `PesananService.php` (dihapus) |
| `metode_pembayaran` | (tidak ada) | `PesananService.php` (dihapus) |

---

## 3. Relasi Model Tidak Sesuai

| File | Baris | Sebelum (SALAH) | Sesudah (BENAR) |
|---|---|---|---|
| `PesananApiController.php` | 29, 138, 166-171 | `$pesanan->formPesanan` | `$pesanan->form` |

Relasi pada model `Pesanan` bernama `form()`, bukan `formPesanan()`.

---

## 4. State Machine Invalid Transition

### `PembayaranService.php :: verifyPayment()`

**Sebelum:**
```
MENUNGGU_VERIFIKASI_PEMBAYARAN → SEDANG_DIPROSES (ERROR: lompat 2 langkah)
```

**Sesudah:**
```
MENUNGGU_VERIFIKASI_PEMBAYARAN → DIKONFIRMASI (step 1)
DIKONFIRMASI → SEDANG_DIPROSES (step 2)
```

Transisi `MENUNGGU_VERIFIKASI_PEMBAYARAN → DIKONFIRMASI` diikuti `DIKONFIRMASI → SEDANG_DIPROSES` via 2 kali panggil `updateStatus()`.

---

## 5. PaymentStatus Enum Tidak Sinkron Database

### Sebelum:
```php
case PENDING = 'pending';      // DB: 'menunggu_pembayaran'
case VERIFIED = 'verified';    // DB: 'sudah_dibayar'
case REJECTED = 'rejected';    // DB: 'ditolak'
```

### Sesudah:
```php
case PENDING = 'menunggu_pembayaran';
case VERIFIED = 'sudah_dibayar';
case REJECTED = 'ditolak';
```

---

## 6. Status Value Tidak Valid

| File | Baris | Sebelum (SALAH) | Sesudah (BENAR) |
|---|---|---|---|
| `PesananApiController.php :: store()` | 83 | `'menunggu_verifikasi'` | `'menunggu_konfirmasi_admin'` |
| `PesananApiController.php :: store()` (form) | 103 | `'menunggu'` | `'pending'` |
| `PesananApiController.php :: uploadBukti()` | 238 | `'menunggu_konfirmasi'` | `'menunggu_verifikasi_pembayaran'` |
| `PesananApiController.php :: uploadBukti()` (payment) | 244 | `'menunggu_verifikasi'` | `'menunggu_pembayaran'` |

### Timeline Pesanan (PesananApiController.php)

Timeline diperbaiki untuk mencerminkan alur status yang benar:
1. `menunggu_konfirmasi_admin` → Pesanan Dibuat
2. `menunggu_pembayaran` → Dikonfirmasi Admin
3. `dikonfirmasi` → Pembayaran Terverifikasi
4. `sedang_diproses` → Sedang Diproses
5. `selesai` → Selesai

---

## 7. Perbaikan Minor

| File | Perbaikan |
|---|---|
| `AdminPembayaranController.php` | Default filter status dari `'pending'` → `'menunggu_pembayaran'` |
| `AdminDashboardController.php` | Join `detail_pesanans.id_layanan` → `detail_pesanans.id_paket` |
| `PesananService.php` | Hapus redundan `array_map()` di validasi transisi |

---

## Daftar File yang Dimodifikasi

| No | File | Kategori Perbaikan |
|---|---|---|
| 1 | `app/Models/ProfilPerusahaan.php` | **BARU** - Model baru |
| 2 | `app/Enums/PaymentStatus.php` | Sinkron nilai enum dengan DB |
| 3 | `app/Models/Pembayaran.php` | Cast key `status_pembayaran` → `status` |
| 4 | `app/Http/Resources/PembayaranResource.php` | Attribute names + hapus `nomor_referensi` |
| 5 | `app/Http/Controllers/Api/Admin/AdminPembayaranController.php` | Column names + default filter |
| 6 | `app/Http/Controllers/Api/Admin/AdminDashboardController.php` | Column names + join key |
| 7 | `app/Services/PembayaranService.php` | Column names + state transition (2 langkah) |
| 8 | `app/Services/PesananService.php` | Column names + hapus kolom tidak ada |
| 9 | `app/Services/KeranjangService.php` | Column names + hapus `custom_data` |
| 10 | `app/Http/Controllers/Api/PesananApiController.php` | Relasi `formPesanan` → `form`, status values, timeline |