<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class ContentSettings extends Settings
{
    public ?string $nav_beranda = null;
    public ?string $nav_layanan = null;
    public ?string $nav_galeri = null;
    public ?string $nav_tentang_kami = null;
    public ?string $nav_masuk = null;
    public ?string $nav_daftar = null;
    public ?string $nav_dashboard = null;
    public ?string $nav_keluar = null;
    public ?string $nav_profil_perusahaan = null;
    public ?string $nav_keranjang = null;
    public ?string $nav_pesanan_saya = null;
    public ?string $nav_profil_saya = null;
    public ?string $nav_bantuan = null;
    public ?string $nav_pengaturan = null;
    public ?string $nav_manajemen = null;
    public ?string $nav_belanja = null;
    public ?string $nav_riwayat_pesanan = null;
    public ?string $nav_pembayaran = null;
    public ?string $nav_pemesanan = null;

    public ?string $footer_tentang = null;
    public ?string $footer_layanan = null;
    public ?string $footer_kebijakan_privasi = null;
    public ?string $footer_hubungi_kami = null;
    public ?string $footer_navigasi = null;
    public ?string $footer_lokasi = null;
    public ?string $footer_copyright = null;
    public ?string $footer_instagram = null;
    public ?string $footer_facebook = null;

    public ?string $sidebar_pesan_baru = null;
    public ?string $sidebar_galeri_portofolio = null;
    public ?string $sidebar_layanan_paket = null;

    public ?string $status_menunggu_pembayaran = null;
    public ?string $status_menunggu_konfirmasi = null;
    public ?string $status_bukti_diterima = null;
    public ?string $status_pembayaran_terverifikasi = null;
    public ?string $status_pesanan_selesai = null;
    public ?string $status_pesanan_ditolak = null;
    public ?string $status_dikonfirmasi = null;
    public ?string $status_dikerjakan = null;
    public ?string $status_pengerjaan_selesai = null;
    public ?string $status_lunas = null;
    public ?string $status_verifikasi_pembayaran = null;

    public ?string $cta_pesan_sekarang = null;
    public ?string $cta_pesan = null;
    public ?string $cta_pesan_lagi = null;
    public ?string $cta_bayar_sekarang = null;
    public ?string $cta_tambah_keranjang = null;
    public ?string $cta_konfirmasi_pembayaran = null;
    public ?string $cta_konfirmasi_pemesanan = null;
    public ?string $cta_selengkapnya = null;
    public ?string $cta_hubungi_wa = null;
    public ?string $cta_hubungi_sekarang = null;
    public ?string $cta_pelajari = null;
    public ?string $cta_lihat_semua = null;
    public ?string $cta_kembali = null;
    public ?string $cta_simpan = null;
    public ?string $cta_hapus = null;
    public ?string $cta_edit = null;
    public ?string $cta_detail = null;
    public ?string $cta_kosongkan = null;
    public ?string $cta_tambah_lainnya = null;
    public ?string $cta_buka_maps = null;
    public ?string $cta_daftar_sekarang = null;
    public ?string $cta_masuk_sekarang = null;
    public ?string $cta_unduh_invoice = null;
    public ?string $cta_explore_layanan = null;
    public ?string $cta_pilih_layanan = null;
    public ?string $cta_lanjutkan_review = null;
    public ?string $cta_salin_rekening = null;
    public ?string $cta_cek_syarat = null;

    public ?string $empty_keranjang_title = null;
    public ?string $empty_keranjang_desc = null;
    public ?string $empty_pesanan_title = null;
    public ?string $empty_pesanan_desc = null;
    public ?string $empty_pembayaran_title = null;
    public ?string $empty_pembayaran_desc = null;
    public ?string $empty_galeri_title = null;
    public ?string $empty_galeri_desc = null;
    public ?string $empty_paket_title = null;
    public ?string $empty_paket_desc = null;
    public ?string $empty_aktivitas_title = null;

    public ?string $section_katalog_layanan = null;
    public ?string $section_pilih_layanan = null;
    public ?string $section_layanan_terpilih = null;
    public ?string $section_detail_kendaraan = null;
    public ?string $section_jadwal_sesi = null;
    public ?string $section_rincian_biaya = null;
    public ?string $section_review = null;
    public ?string $section_pembayaran = null;
    public ?string $section_paket_kami = null;
    public ?string $section_layanan_cepat = null;
    public ?string $section_ringkasan_pesanan = null;
    public ?string $section_upload_bukti = null;
    public ?string $section_data_kendaraan = null;

    public ?string $form_nama_lengkap = null;
    public ?string $form_email = null;
    public ?string $form_nomor_telepon = null;
    public ?string $form_password = null;
    public ?string $form_konfirmasi_password = null;
    public ?string $form_merk_kendaraan = null;
    public ?string $form_warna_kendaraan = null;
    public ?string $form_nomor_polisi = null;
    public ?string $form_tahun_produksi = null;
    public ?string $form_nama_pemesan = null;
    public ?string $form_lokasi_pengerjaan = null;
    public ?string $form_alamat_pengerjaan = null;
    public ?string $form_tanggal_mulai = null;
    public ?string $form_whatsapp = null;
    public ?string $form_studio_hq = null;
    public ?string $form_home_service = null;
    public ?string $form_lupa_sandi = null;
    public ?string $form_ingat_saya = null;
    public ?string $form_setuju_syarat = null;
    public ?string $form_kode_promo = null;

    public ?string $label_nama_bank = null;
    public ?string $label_no_rekening = null;
    public ?string $label_atas_nama = null;
    public ?string $label_total_bayar = null;
    public ?string $label_metode_pembayaran = null;
    public ?string $label_estimasi_selesai = null;
    public ?string $label_subtotal = null;
    public ?string $label_biaya_admin = null;
    public ?string $label_total_tagihan = null;
    public ?string $label_dicetak = null;
    public ?string $label_member_status = null;

    public ?string $invoice_billed_to = null;
    public ?string $invoice_spesifikasi = null;
    public ?string $invoice_thankyou = null;
    public ?string $invoice_legal = null;

    public ?string $auth_selamat_datang = null;
    public ?string $auth_lupa_sandi_text = null;
    public ?string $auth_verifikasi_text = null;
    public ?string $auth_area_aman = null;

    public ?string $alert_konfirmasi_kosongkan = null;
    public ?string $alert_hapus_keranjang = null;
    public ?string $alert_rekening_disalin = null;
    public ?string $alert_lengkapi_data = null;

    public ?string $layanan_garansi_text = null;
    public ?string $layanan_pembayaran_aman = null;
    public ?string $checkout_terms_text = null;
    public ?string $checkout_review_prompt = null;
    public ?string $checkout_lengkapi_prompt = null;

    public ?string $welcome_title = null;
    public ?string $welcome_subtitle = null;

    public static function group(): string
    {
        return 'content';
    }
}
