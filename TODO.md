# Audit & Perbaikan — Informasi Pemesanan (Laravel + Filament)

## Step 1 — Repo Understanding
- [x] Ambil gambaran struktur awal (folder app/database/routes)
- [x] Baca `routes/web.php`, `routes/api.php`
- [x] Baca core services & model pemesanan: `PesananService`, `CartService`, `KeranjangService`, `PembayaranService`
- [x] Baca model terkait: `Pesanan`, `Keranjang`, `DetailPesanan`, `DetailKeranjang`, `Pembayaran`, `Notifikasi`, `Layanan`
- [x] Baca migration utama `pesanans` dan `detail_pesanans`

## Step 2 — Brainstorm Plan (Audit)
- [x] Identifikasi mismatch status enum vs migration/model (MAJOR RISK)
- [ ] Identifikasi potensi bug logic & race condition (checkout, upload bukti, verify/reject)
- [ ] Identifikasi keamanan endpoint & file upload
- [ ] Identifikasi N+1 & eager loading (controller/resource)
- [ ] Identifikasi arsitektur/clean architecture (service/repository absence)
- [ ] Identifikasi foreign key, indexing, normalisasi
- [ ] Susun rekomendasi perubahan minimal tapi aman untuk production

## Step 3 — Implementation (disetujui)
- [x] Pilih dan implement perbaikan status mismatch: `pesanans.status` -> string (tanpa enum) agar align dengan `OrderStatus` di application layer

- [ ] Perbaiki checkout & max-3 logic agar anti-race
- [ ] Perbaiki payment flow: status field consistency, locking, idempotency
- [ ] Perbaiki konsistensi API response/error handling
- [ ] Tambah testing baseline untuk flow pemesanan

## Step 4 — Testing & Validation
- [ ] Jalankan PHPUnit/Pest
- [ ] Jalankan smoke test endpoint
- [ ] Verifikasi performa query (cek eager loading)

## Step 5 — Final Documentation
- [ ] Dokumentasi flow pemesanan + status transitions
- [ ] Dokumentasi struktur database (relasi & indexing)
- [ ] Dokumentasi API (routes & payload)

