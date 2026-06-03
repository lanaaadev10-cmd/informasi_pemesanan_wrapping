<?php

namespace App\Filament\Resources\Contents\Pages;

use App\Filament\Resources\ContentResource;
use App\Settings\ContentSettings;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Model;

class EditContent extends EditRecord
{
    protected static string $resource = ContentResource::class;

    protected static ?string $title = 'Edit Konten UI';

    public function mount($record = null): void
    {
        $this->record = $this->getRecord();
        $this->authorizeAccess();
        $settings = app(ContentSettings::class);
        $this->form->fill($settings->toArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Navigasi')
                    ->description('Label navigasi utama dan sidebar.')
                    ->icon('heroicon-o-bars-3')
                    ->schema([
                        Grid::make(3)->schema([
                            TextInput::make('nav_beranda')->label('Beranda'),
                            TextInput::make('nav_layanan')->label('Layanan'),
                            TextInput::make('nav_galeri')->label('Galeri'),
                            TextInput::make('nav_tentang_kami')->label('Tentang Kami'),
                            TextInput::make('nav_masuk')->label('Masuk'),
                            TextInput::make('nav_daftar')->label('Daftar'),
                            TextInput::make('nav_dashboard')->label('Dashboard'),
                            TextInput::make('nav_keluar')->label('Keluar'),
                            TextInput::make('nav_profil_perusahaan')->label('Profil Perusahaan'),
                            TextInput::make('nav_keranjang')->label('Keranjang'),
                            TextInput::make('nav_pesanan_saya')->label('Pesanan Saya'),
                            TextInput::make('nav_profil_saya')->label('Profil Saya'),
                            TextInput::make('nav_bantuan')->label('Bantuan'),
                            TextInput::make('nav_pengaturan')->label('Pengaturan'),
                            TextInput::make('nav_manajemen')->label('Manajemen'),
                            TextInput::make('nav_belanja')->label('Belanja'),
                            TextInput::make('nav_riwayat_pesanan')->label('Riwayat Pesanan'),
                            TextInput::make('nav_pembayaran')->label('Pembayaran'),
                            TextInput::make('nav_pemesanan')->label('Pemesanan'),
                        ]),
                        Grid::make(2)->schema([
                            TextInput::make('sidebar_pesan_baru')->label('Sidebar: Pesan Baru'),
                            TextInput::make('sidebar_galeri_portofolio')->label('Sidebar: Galeri'),
                            TextInput::make('sidebar_layanan_paket')->label('Sidebar: Layanan & Paket'),
                        ]),
                    ]),

                Section::make('Footer')
                    ->description('Teks footer website.')
                    ->icon('heroicon-o-chevron-double-down')
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('footer_tentang')->label('Tentang (link)'),
                            TextInput::make('footer_layanan')->label('Layanan (link)'),
                            TextInput::make('footer_kebijakan_privasi')->label('Kebijakan Privasi'),
                            TextInput::make('footer_hubungi_kami')->label('Hubungi Kami'),
                            TextInput::make('footer_navigasi')->label('Judul Navigasi'),
                            TextInput::make('footer_lokasi')->label('Lokasi Kami'),
                            TextInput::make('footer_instagram')->label('Instagram'),
                            TextInput::make('footer_facebook')->label('Facebook'),
                        ]),
                        TextInput::make('footer_copyright')->label('Copyright')->columnSpanFull(),
                    ]),

                Section::make('Status Pesanan')
                    ->description('Label status pesanan.')
                    ->icon('heroicon-o-check-badge')
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        Grid::make(3)->schema([
                            TextInput::make('status_menunggu_pembayaran')->label('Menunggu Pembayaran'),
                            TextInput::make('status_menunggu_konfirmasi')->label('Menunggu Konfirmasi'),
                            TextInput::make('status_bukti_diterima')->label('Bukti Diterima'),
                            TextInput::make('status_pembayaran_terverifikasi')->label('Pembayaran Terverifikasi'),
                            TextInput::make('status_pesanan_selesai')->label('Pesanan Selesai'),
                            TextInput::make('status_pesanan_ditolak')->label('Pesanan Ditolak'),
                            TextInput::make('status_dikonfirmasi')->label('Dikonfirmasi'),
                            TextInput::make('status_dikerjakan')->label('Sedang Dikerjakan'),
                            TextInput::make('status_pengerjaan_selesai')->label('Pengerjaan Selesai'),
                            TextInput::make('status_lunas')->label('LUNAS'),
                            TextInput::make('status_verifikasi_pembayaran')->label('Verifikasi Pembayaran'),
                        ]),
                    ]),

                Section::make('Tombol / CTA')
                    ->description('Label tombol di seluruh website.')
                    ->icon('heroicon-o-cursor-arrow-rays')
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        Grid::make(3)->schema([
                            TextInput::make('cta_pesan_sekarang')->label('Pesan Sekarang'),
                            TextInput::make('cta_pesan')->label('Pesan'),
                            TextInput::make('cta_pesan_lagi')->label('Pesan Lagi'),
                            TextInput::make('cta_bayar_sekarang')->label('Bayar Sekarang'),
                            TextInput::make('cta_tambah_keranjang')->label('Tambah Keranjang'),
                            TextInput::make('cta_konfirmasi_pembayaran')->label('Konfirmasi Pembayaran'),
                            TextInput::make('cta_konfirmasi_pemesanan')->label('Konfirmasi Pemesanan'),
                            TextInput::make('cta_selengkapnya')->label('Selengkapnya'),
                            TextInput::make('cta_hubungi_wa')->label('Hubungi WhatsApp'),
                            TextInput::make('cta_hubungi_sekarang')->label('Hubungi Kami'),
                            TextInput::make('cta_pelajari')->label('Pelajari Prosedur'),
                            TextInput::make('cta_lihat_semua')->label('Lihat Semua'),
                            TextInput::make('cta_kembali')->label('Kembali'),
                            TextInput::make('cta_simpan')->label('Simpan'),
                            TextInput::make('cta_hapus')->label('Hapus'),
                            TextInput::make('cta_edit')->label('Edit'),
                            TextInput::make('cta_detail')->label('Detail'),
                            TextInput::make('cta_kosongkan')->label('Kosongkan'),
                            TextInput::make('cta_tambah_lainnya')->label('Tambah Lainnya'),
                            TextInput::make('cta_buka_maps')->label('Buka Maps'),
                            TextInput::make('cta_daftar_sekarang')->label('Daftar Sekarang'),
                            TextInput::make('cta_masuk_sekarang')->label('Masuk Sekarang'),
                            TextInput::make('cta_unduh_invoice')->label('Unduh Invoice'),
                            TextInput::make('cta_explore_layanan')->label('Explore Layanan'),
                            TextInput::make('cta_pilih_layanan')->label('Pilih Layanan'),
                            TextInput::make('cta_lanjutkan_review')->label('Lanjut Review'),
                            TextInput::make('cta_salin_rekening')->label('Salin Rekening'),
                            TextInput::make('cta_cek_syarat')->label('Cek Syarat'),
                        ]),
                    ]),

                Section::make('Label Informasi')
                    ->description('Label pembayaran, invoice, dll.')
                    ->icon('heroicon-o-information-circle')
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        Grid::make(3)->schema([
                            TextInput::make('label_nama_bank')->label('Nama Bank'),
                            TextInput::make('label_no_rekening')->label('No. Rekening'),
                            TextInput::make('label_atas_nama')->label('Atas Nama'),
                            TextInput::make('label_total_bayar')->label('Total Dibayar'),
                            TextInput::make('label_metode_pembayaran')->label('Metode Pembayaran'),
                            TextInput::make('label_estimasi_selesai')->label('Estimasi Selesai'),
                            TextInput::make('label_subtotal')->label('Subtotal'),
                            TextInput::make('label_biaya_admin')->label('Biaya Admin'),
                            TextInput::make('label_total_tagihan')->label('Total Tagihan'),
                            TextInput::make('label_dicetak')->label('Dicetak'),
                            TextInput::make('label_member_status')->label('Member Status'),
                        ]),
                    ]),

                Section::make('Form Fields')
                    ->description('Label field form input.')
                    ->icon('heroicon-o-document-text')
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        Grid::make(3)->schema([
                            TextInput::make('form_nama_lengkap')->label('Nama Lengkap'),
                            TextInput::make('form_email')->label('Email'),
                            TextInput::make('form_nomor_telepon')->label('Nomor Telepon'),
                            TextInput::make('form_password')->label('Password'),
                            TextInput::make('form_konfirmasi_password')->label('Konfirmasi Password'),
                            TextInput::make('form_merk_kendaraan')->label('Merk & Model'),
                            TextInput::make('form_warna_kendaraan')->label('Warna Dasar'),
                            TextInput::make('form_nomor_polisi')->label('Nomor Polisi'),
                            TextInput::make('form_tahun_produksi')->label('Tahun Produksi'),
                            TextInput::make('form_nama_pemesan')->label('Nama Pemesan'),
                            TextInput::make('form_lokasi_pengerjaan')->label('Lokasi'),
                            TextInput::make('form_alamat_pengerjaan')->label('Alamat'),
                            TextInput::make('form_tanggal_mulai')->label('Tanggal Mulai'),
                            TextInput::make('form_whatsapp')->label('WhatsApp'),
                            TextInput::make('form_studio_hq')->label('Studio HQ'),
                            TextInput::make('form_home_service')->label('Home Service'),
                            TextInput::make('form_lupa_sandi')->label('Lupa Sandi'),
                            TextInput::make('form_ingat_saya')->label('Ingat Saya'),
                            TextInput::make('form_kode_promo')->label('Kode Promo'),
                        ]),
                        Textarea::make('form_setuju_syarat')->label('Syarat & Ketentuan')->rows(2)->columnSpanFull(),
                    ]),

                Section::make('Empty State')
                    ->description('Teks saat tidak ada data.')
                    ->icon('heroicon-o-inbox')
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('empty_keranjang_title')->label('Keranjang Kosong'),
                            Textarea::make('empty_keranjang_desc')->label('Deskripsi')->rows(2),
                            TextInput::make('empty_pesanan_title')->label('Belum Ada Pesanan'),
                            Textarea::make('empty_pesanan_desc')->label('Deskripsi')->rows(2),
                            TextInput::make('empty_pembayaran_title')->label('Belum Ada Tagihan'),
                            Textarea::make('empty_pembayaran_desc')->label('Deskripsi')->rows(2),
                            TextInput::make('empty_galeri_title')->label('Belum Ada Galeri'),
                            Textarea::make('empty_galeri_desc')->label('Deskripsi')->rows(2),
                            TextInput::make('empty_paket_title')->label('Paket Kosong'),
                            Textarea::make('empty_paket_desc')->label('Deskripsi')->rows(2),
                            TextInput::make('empty_aktivitas_title')->label('Belum Ada Aktivitas'),
                        ]),
                    ]),

                Section::make('Invoice')
                    ->description('Teks halaman invoice.')
                    ->icon('heroicon-o-document-currency-dollar')
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('invoice_billed_to')->label('Billed To'),
                            TextInput::make('invoice_spesifikasi')->label('Spesifikasi'),
                            TextInput::make('invoice_thankyou')->label('Thank You Text'),
                            Textarea::make('invoice_legal')->label('Legal Disclaimer')->rows(2),
                        ]),
                    ]),

                Section::make('Halaman Auth')
                    ->description('Teks halaman login, register, forgot password.')
                    ->icon('heroicon-o-lock-closed')
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('auth_selamat_datang')->label('Selamat Datang'),
                            TextInput::make('auth_lupa_sandi_text')->label('Lupa Sandi Text'),
                            TextInput::make('auth_verifikasi_text')->label('Verifikasi Text'),
                            Textarea::make('auth_area_aman')->label('Area Aman Text')->rows(2),
                        ]),
                    ]),

                Section::make('Alert & Konfirmasi')
                    ->description('Teks dialog konfirmasi dan alert.')
                    ->icon('heroicon-o-exclamation-triangle')
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        Grid::make(2)->schema([
                            Textarea::make('alert_konfirmasi_kosongkan')->label('Konfirmasi Kosongkan')->rows(2),
                            Textarea::make('alert_hapus_keranjang')->label('Konfirmasi Hapus')->rows(2),
                            TextInput::make('alert_rekening_disalin')->label('Notifikasi Rekening'),
                            Textarea::make('alert_lengkapi_data')->label('Peringatan Lengkapi Data')->rows(2),
                        ]),
                    ]),

                Section::make('Section Titles')
                    ->description('Judul section di berbagai halaman.')
                    ->icon('heroicon-o-tag')
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('section_katalog_layanan')->label('Katalog Layanan'),
                            TextInput::make('section_pilih_layanan')->label('Pilih Layanan'),
                            TextInput::make('section_layanan_terpilih')->label('Layanan Terpilih'),
                            TextInput::make('section_detail_kendaraan')->label('Detail Kendaraan'),
                            TextInput::make('section_jadwal_sesi')->label('Jadwal Sesi'),
                            TextInput::make('section_rincian_biaya')->label('Rincian Biaya'),
                            TextInput::make('section_review')->label('Review'),
                            TextInput::make('section_pembayaran')->label('Pembayaran'),
                            TextInput::make('section_paket_kami')->label('Paket Layanan Kami'),
                            TextInput::make('section_layanan_cepat')->label('Layanan Cepat'),
                            TextInput::make('section_ringkasan_pesanan')->label('Ringkasan Pesanan'),
                            TextInput::make('section_upload_bukti')->label('Upload Bukti'),
                            TextInput::make('section_data_kendaraan')->label('Data Kendaraan'),
                        ]),
                    ]),

                Section::make('Others')
                    ->description('Teks lainnya.')
                    ->icon('heroicon-o-ellipsis-horizontal')
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        Grid::make(2)->schema([
                            Textarea::make('layanan_garansi_text')->label('Garansi Layanan')->rows(2),
                            Textarea::make('layanan_pembayaran_aman')->label('Pembayaran Aman')->rows(2),
                            Textarea::make('checkout_terms_text')->label('Syarat Checkout')->rows(2),
                            Textarea::make('checkout_review_prompt')->label('Prompt Review')->rows(2),
                            Textarea::make('checkout_lengkapi_prompt')->label('Prompt Lengkapi Data')->rows(2),
                            TextInput::make('welcome_title')->label('Welcome Title'),
                            Textarea::make('welcome_subtitle')->label('Welcome Subtitle')->rows(2),
                        ]),
                    ]),
            ]);
    }

    public function save(bool $shouldRedirect = true, bool $shouldSendSavedNotification = true): void
    {
        $this->callHook('beforeValidate');
        $data = $this->form->getState();
        $this->callHook('beforeSave');

        $settings = app(ContentSettings::class);
        foreach ($data as $key => $value) {
            $settings->{$key} = $value;
        }
        $settings->save();
        $this->callHook('afterSave');

        Notification::make()
            ->success()
            ->title('Konten berhasil diperbarui!')
            ->send();
    }

    public function getRecord(): Model
    {
        return new \App\Models\DummyModel;
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('view_website')
                ->label('Lihat Website')
                ->icon('heroicon-m-arrow-top-right-on-square')
                ->color('gray')
                ->url(url('/'))
                ->openUrlInNewTab(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function authorizeAccess(): void
    {
        abort_unless(static::getResource()::canViewAny(), 403);
    }
}
