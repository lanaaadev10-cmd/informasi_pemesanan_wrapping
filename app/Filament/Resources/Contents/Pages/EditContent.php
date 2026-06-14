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
        $data = $settings->toArray();
        $defaults = (new \ReflectionClass($settings))->getDefaultProperties();
        $this->form->fill(array_merge($defaults, $data));
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
                            TextInput::make('nav_beranda')->label('Beranda')->helperText('Label menu navigasi untuk halaman utama.'),
                            TextInput::make('nav_layanan')->label('Layanan')->helperText('Label menu navigasi untuk halaman layanan/paket.'),
                            TextInput::make('nav_galeri')->label('Galeri')->helperText('Label menu navigasi untuk halaman galeri portofolio.'),
                            TextInput::make('nav_tentang_kami')->label('Tentang Kami')->helperText('Label menu navigasi untuk halaman tentang perusahaan.'),
                            TextInput::make('nav_masuk')->label('Masuk')->helperText('Label tombol login untuk pelanggan.'),
                            TextInput::make('nav_daftar')->label('Daftar')->helperText('Label tombol register/sign up.'),
                            TextInput::make('nav_dashboard')->label('Dashboard')->helperText('Label menu dashboard pelanggan.'),
                            TextInput::make('nav_keluar')->label('Keluar')->helperText('Label tombol logout/sign out.'),
                            TextInput::make('nav_profil_perusahaan')->label('Profil Perusahaan')->helperText('Label menu profil perusahaan.'),
                            TextInput::make('nav_keranjang')->label('Keranjang')->helperText('Label menu shopping cart.'),
                            TextInput::make('nav_pesanan_saya')->label('Pesanan Saya')->helperText('Label menu untuk melihat pesanan pelanggan.'),
                            TextInput::make('nav_profil_saya')->label('Profil Saya')->helperText('Label menu untuk mengedit profil pelanggan.'),
                            TextInput::make('nav_bantuan')->label('Bantuan')->helperText('Label menu bantuan/FAQ.'),
                            TextInput::make('nav_pengaturan')->label('Pengaturan')->helperText('Label menu pengaturan akun.'),
                            TextInput::make('nav_manajemen')->label('Manajemen')->helperText('Label menu admin/manajemen.'),
                            TextInput::make('nav_belanja')->label('Belanja')->helperText('Label untuk section berbelanja.'),
                            TextInput::make('nav_riwayat_pesanan')->label('Riwayat Pesanan')->helperText('Label untuk history pesanan.'),
                            TextInput::make('nav_pembayaran')->label('Pembayaran')->helperText('Label untuk section pembayaran.'),
                            TextInput::make('nav_pemesanan')->label('Pemesanan')->helperText('Label untuk proses pemesanan.'),
                        ]),
                        Grid::make(2)->schema([
                            TextInput::make('sidebar_pesan_baru')->label('Sidebar: Pesan Baru')->helperText('Label notifikasi pesan baru di sidebar.'),
                            TextInput::make('sidebar_galeri_portofolio')->label('Sidebar: Galeri')->helperText('Label section galeri di sidebar.'),
                            TextInput::make('sidebar_layanan_paket')->label('Sidebar: Layanan & Paket')->helperText('Label section layanan/paket di sidebar.'),
                        ]),
                    ]),

                Section::make('Footer')
                    ->description('Teks footer website.')
                    ->icon('heroicon-o-chevron-double-down')
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('footer_tentang')->label('Tentang (link)')->helperText('Label link "Tentang" di footer.'),
                            TextInput::make('footer_layanan')->label('Layanan (link)')->helperText('Label link "Layanan" di footer.'),
                            TextInput::make('footer_kebijakan_privasi')->label('Kebijakan Privasi')->helperText('Label link kebijakan privasi.'),
                            TextInput::make('footer_hubungi_kami')->label('Hubungi Kami')->helperText('Label link hubungi kami.'),
                            TextInput::make('footer_navigasi')->label('Judul Navigasi')->helperText('Judul section navigasi di footer.'),
                            TextInput::make('footer_lokasi')->label('Lokasi Kami')->helperText('Judul section lokasi/alamat perusahaan.'),
                            TextInput::make('footer_instagram')->label('Instagram')->helperText('Username atau handle Instagram (untuk link sosial media).'),
                            TextInput::make('footer_facebook')->label('Facebook')->helperText('Nama halaman/profil Facebook (untuk link sosial media).'),
                        ]),
                        TextInput::make('footer_copyright')->label('Copyright')->helperText('Teks copyright/hak cipta di footer bawah (contoh: © 2024 PT Company Name).')->columnSpanFull(),
                    ]),

                Section::make('Status Pesanan')
                    ->description('Label status pesanan.')
                    ->icon('heroicon-o-check-badge')
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        Grid::make(3)->schema([
                            TextInput::make('status_menunggu_pembayaran')->label('Menunggu Pembayaran')->helperText('Label status: Pesanan dalam menunggu pembayaran.'),
                            TextInput::make('status_menunggu_konfirmasi')->label('Menunggu Konfirmasi')->helperText('Label status: Menunggu konfirmasi dari admin.'),
                            TextInput::make('status_bukti_diterima')->label('Bukti Diterima')->helperText('Label status: Bukti pembayaran sudah diterima.'),
                            TextInput::make('status_pembayaran_terverifikasi')->label('Pembayaran Terverifikasi')->helperText('Label status: Pembayaran sudah diverifikasi.'),
                            TextInput::make('status_pesanan_selesai')->label('Pesanan Selesai')->helperText('Label status: Pesanan telah selesai dikerjakan.'),
                            TextInput::make('status_pesanan_ditolak')->label('Pesanan Ditolak')->helperText('Label status: Pesanan ditolak (pembayaran gagal/dibatalkan).'),
                            TextInput::make('status_dikonfirmasi')->label('Dikonfirmasi')->helperText('Label status: Pesanan sudah dikonfirmasi admin.'),
                            TextInput::make('status_dikerjakan')->label('Sedang Dikerjakan')->helperText('Label status: Pekerjaan sedang berlangsung.'),
                            TextInput::make('status_pengerjaan_selesai')->label('Pengerjaan Selesai')->helperText('Label status: Pengerjaan sudah selesai, siap diambil.'),
                            TextInput::make('status_lunas')->label('LUNAS')->helperText('Label status: Pesanan lunas/pembayaran penuh diterima.'),
                            TextInput::make('status_verifikasi_pembayaran')->label('Verifikasi Pembayaran')->helperText('Label status: Pembayaran dalam proses verifikasi.'),
                        ]),
                    ]),

                Section::make('Tombol / CTA')
                    ->description('Label tombol di seluruh website.')
                    ->icon('heroicon-o-cursor-arrow-rays')
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        Grid::make(3)->schema([
                            TextInput::make('cta_pesan_sekarang')->label('Pesan Sekarang')->helperText('Tombol untuk memesan layanan sekarang.'),
                            TextInput::make('cta_pesan')->label('Pesan')->helperText('Tombol pesan (versi pendek).'),
                            TextInput::make('cta_pesan_lagi')->label('Pesan Lagi')->helperText('Tombol untuk memesan layanan yang sama kembali.'),
                            TextInput::make('cta_bayar_sekarang')->label('Bayar Sekarang')->helperText('Tombol untuk melakukan pembayaran.'),
                            TextInput::make('cta_tambah_keranjang')->label('Tambah Keranjang')->helperText('Tombol untuk menambah item ke keranjang.'),
                            TextInput::make('cta_konfirmasi_pembayaran')->label('Konfirmasi Pembayaran')->helperText('Tombol untuk konfirmasi pembayaran telah dilakukan.'),
                            TextInput::make('cta_konfirmasi_pemesanan')->label('Konfirmasi Pemesanan')->helperText('Tombol untuk konfirmasi pesanan.'),
                            TextInput::make('cta_selengkapnya')->label('Selengkapnya')->helperText('Tombol untuk melihat detail lengkap.'),
                            TextInput::make('cta_hubungi_wa')->label('Hubungi WhatsApp')->helperText('Tombol untuk menghubungi via WhatsApp.'),
                            TextInput::make('cta_hubungi_sekarang')->label('Hubungi Kami')->helperText('Tombol untuk menghubungi perusahaan.'),
                            TextInput::make('cta_pelajari')->label('Pelajari Prosedur')->helperText('Tombol untuk mempelajari prosedur/tata cara.'),
                            TextInput::make('cta_lihat_semua')->label('Lihat Semua')->helperText('Tombol untuk melihat semua item.'),
                            TextInput::make('cta_kembali')->label('Kembali')->helperText('Tombol untuk kembali ke halaman sebelumnya.'),
                            TextInput::make('cta_simpan')->label('Simpan')->helperText('Tombol untuk menyimpan perubahan.'),
                            TextInput::make('cta_hapus')->label('Hapus')->helperText('Tombol untuk menghapus item.'),
                            TextInput::make('cta_edit')->label('Edit')->helperText('Tombol untuk mengedit/mengubah data.'),
                            TextInput::make('cta_detail')->label('Detail')->helperText('Tombol untuk melihat detail.'),
                            TextInput::make('cta_kosongkan')->label('Kosongkan')->helperText('Tombol untuk mengosongkan keranjang.'),
                            TextInput::make('cta_tambah_lainnya')->label('Tambah Lainnya')->helperText('Tombol untuk menambah item lain.'),
                            TextInput::make('cta_buka_maps')->label('Buka Maps')->helperText('Tombol untuk membuka lokasi di Google Maps.'),
                            TextInput::make('cta_daftar_sekarang')->label('Daftar Sekarang')->helperText('Tombol untuk mendaftar akun baru.'),
                            TextInput::make('cta_masuk_sekarang')->label('Masuk Sekarang')->helperText('Tombol untuk login ke akun.'),
                            TextInput::make('cta_unduh_invoice')->label('Unduh Invoice')->helperText('Tombol untuk mengunduh invoice/bukti pembelian.'),
                            TextInput::make('cta_explore_layanan')->label('Explore Layanan')->helperText('Tombol untuk menjelajahi layanan tersedia.'),
                            TextInput::make('cta_pilih_layanan')->label('Pilih Layanan')->helperText('Tombol untuk memilih layanan.'),
                            TextInput::make('cta_lanjutkan_review')->label('Lanjut Review')->helperText('Tombol untuk lanjut ke review pesanan.'),
                            TextInput::make('cta_salin_rekening')->label('Salin Rekening')->helperText('Tombol untuk copy nomor rekening.'),
                            TextInput::make('cta_cek_syarat')->label('Cek Syarat')->helperText('Tombol untuk mengecek syarat & ketentuan.'),
                        ]),
                    ]),

                Section::make('Label Informasi')
                    ->description('Label pembayaran, invoice, dll.')
                    ->icon('heroicon-o-information-circle')
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        Grid::make(3)->schema([
                            TextInput::make('label_nama_bank')->label('Nama Bank')->helperText('Label untuk menampilkan nama bank.'),
                            TextInput::make('label_no_rekening')->label('No. Rekening')->helperText('Label untuk menampilkan nomor rekening.'),
                            TextInput::make('label_atas_nama')->label('Atas Nama')->helperText('Label untuk nama pemilik rekening.'),
                            TextInput::make('label_total_bayar')->label('Total Dibayar')->helperText('Label untuk total jumlah pembayaran.'),
                            TextInput::make('label_metode_pembayaran')->label('Metode Pembayaran')->helperText('Label untuk metode pembayaran yang digunakan.'),
                            TextInput::make('label_estimasi_selesai')->label('Estimasi Selesai')->helperText('Label untuk estimasi tanggal selesai pengerjaan.'),
                            TextInput::make('label_subtotal')->label('Subtotal')->helperText('Label untuk subtotal harga.'),
                            TextInput::make('label_biaya_admin')->label('Biaya Admin')->helperText('Label untuk biaya administrasi/fee.'),
                            TextInput::make('label_total_tagihan')->label('Total Tagihan')->helperText('Label untuk total tagihan akhir.'),
                            TextInput::make('label_dicetak')->label('Dicetak')->helperText('Label tanggal invoice dicetak.'),
                            TextInput::make('label_member_status')->label('Member Status')->helperText('Label untuk status member/langganan.'),
                        ]),
                    ]),

                Section::make('Form Fields')
                    ->description('Label field form input.')
                    ->icon('heroicon-o-document-text')
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        Grid::make(3)->schema([
                            TextInput::make('form_nama_lengkap')->label('Nama Lengkap')->helperText('Label field nama lengkap.'),
                            TextInput::make('form_email')->label('Email')->helperText('Label field email.'),
                            TextInput::make('form_nomor_telepon')->label('Nomor Telepon')->helperText('Label field nomor telepon.'),
                            TextInput::make('form_password')->label('Password')->helperText('Label field password.'),
                            TextInput::make('form_konfirmasi_password')->label('Konfirmasi Password')->helperText('Label untuk konfirmasi password.'),
                            TextInput::make('form_merk_kendaraan')->label('Merk & Model')->helperText('Label untuk merk & model kendaraan.'),
                            TextInput::make('form_warna_kendaraan')->label('Warna Dasar')->helperText('Label untuk warna kendaraan.'),
                            TextInput::make('form_nomor_polisi')->label('Nomor Polisi')->helperText('Label untuk nomor polisi.'),
                            TextInput::make('form_tahun_produksi')->label('Tahun Produksi')->helperText('Label untuk tahun produksi.'),
                            TextInput::make('form_nama_pemesan')->label('Nama Pemesan')->helperText('Label untuk nama pemesan.'),
                            TextInput::make('form_lokasi_pengerjaan')->label('Lokasi')->helperText('Label untuk lokasi pengerjaan.'),
                            TextInput::make('form_alamat_pengerjaan')->label('Alamat')->helperText('Label untuk alamat lengkap.'),
                            TextInput::make('form_tanggal_mulai')->label('Tanggal Mulai')->helperText('Label untuk tanggal mulai.'),
                            TextInput::make('form_whatsapp')->label('WhatsApp')->helperText('Label untuk nomor WhatsApp.'),
                            TextInput::make('form_studio_hq')->label('Label Lokasi Bengkel')->helperText('Label untuk lokasi pengerjaan di bengkel.'),

                            TextInput::make('form_lupa_sandi')->label('Lupa Sandi')->helperText('Label link lupa sandi.'),
                            TextInput::make('form_ingat_saya')->label('Ingat Saya')->helperText('Label checkbox ingat saya.'),
                            TextInput::make('form_kode_promo')->label('Kode Promo')->helperText('Label field kode promo.'),
                        ]),
                        Textarea::make('form_setuju_syarat')->label('Syarat & Ketentuan')->helperText('Teks syarat & ketentuan yang harus disetujui.')->rows(2)->columnSpanFull(),
                    ]),

                Section::make('Empty State')
                    ->description('Teks saat tidak ada data.')
                    ->icon('heroicon-o-inbox')
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('empty_keranjang_title')->label('Keranjang Kosong')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                            Textarea::make('empty_keranjang_desc')->label('Deskripsi')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-'))->rows(2),
                            TextInput::make('empty_pesanan_title')->label('Belum Ada Pesanan')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                            Textarea::make('empty_pesanan_desc')->label('Deskripsi')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-'))->rows(2),
                            TextInput::make('empty_pembayaran_title')->label('Belum Ada Tagihan')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                            Textarea::make('empty_pembayaran_desc')->label('Deskripsi')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-'))->rows(2),
                            TextInput::make('empty_galeri_title')->label('Belum Ada Galeri')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                            Textarea::make('empty_galeri_desc')->label('Deskripsi')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-'))->rows(2),
                            TextInput::make('empty_paket_title')->label('Paket Kosong')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                            Textarea::make('empty_paket_desc')->label('Deskripsi')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-'))->rows(2),
                            TextInput::make('empty_aktivitas_title')->label('Belum Ada Aktivitas')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                        ]),
                    ]),

                Section::make('Invoice')
                    ->description('Teks halaman invoice.')
                    ->icon('heroicon-o-document-currency-dollar')
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('invoice_billed_to')->label('Billed To')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                            TextInput::make('invoice_spesifikasi')->label('Spesifikasi')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                            TextInput::make('invoice_thankyou')->label('Thank You Text')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                            Textarea::make('invoice_legal')->label('Legal Disclaimer')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-'))->rows(2),
                        ]),
                    ]),

                Section::make('Halaman Auth')
                    ->description('Teks halaman login, register, forgot password.')
                    ->icon('heroicon-o-lock-closed')
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('auth_selamat_datang')->label('Selamat Datang')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                            TextInput::make('auth_lupa_sandi_text')->label('Lupa Sandi Text')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                            TextInput::make('auth_verifikasi_text')->label('Verifikasi Text')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                            Textarea::make('auth_area_aman')->label('Area Aman Text')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-'))->rows(2),
                        ]),
                    ]),

                Section::make('Alert & Konfirmasi')
                    ->description('Teks dialog konfirmasi dan alert.')
                    ->icon('heroicon-o-exclamation-triangle')
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        Grid::make(2)->schema([
                            Textarea::make('alert_konfirmasi_kosongkan')->label('Konfirmasi Kosongkan')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-'))->rows(2),
                            Textarea::make('alert_hapus_keranjang')->label('Konfirmasi Hapus')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-'))->rows(2),
                            TextInput::make('alert_rekening_disalin')->label('Notifikasi Rekening')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                            Textarea::make('alert_lengkapi_data')->label('Peringatan Lengkapi Data')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-'))->rows(2),
                        ]),
                    ]),

                Section::make('Section Titles')
                    ->description('Judul section di berbagai halaman.')
                    ->icon('heroicon-o-tag')
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('section_katalog_layanan')->label('Katalog Layanan')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                            TextInput::make('section_pilih_layanan')->label('Pilih Layanan')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                            TextInput::make('section_layanan_terpilih')->label('Layanan Terpilih')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                            TextInput::make('section_detail_kendaraan')->label('Detail Kendaraan')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                            TextInput::make('section_jadwal_sesi')->label('Jadwal Sesi')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                            TextInput::make('section_rincian_biaya')->label('Rincian Biaya')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                            TextInput::make('section_review')->label('Review')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                            TextInput::make('section_pembayaran')->label('Pembayaran')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                            TextInput::make('section_paket_kami')->label('Paket Layanan Kami')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                            TextInput::make('section_layanan_cepat')->label('Layanan Cepat')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                            TextInput::make('section_ringkasan_pesanan')->label('Ringkasan Pesanan')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                            TextInput::make('section_upload_bukti')->label('Upload Bukti')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                            TextInput::make('section_data_kendaraan')->label('Data Kendaraan')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                        ]),
                    ]),

                Section::make('Others')
                    ->description('Teks lainnya.')
                    ->icon('heroicon-o-ellipsis-horizontal')
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        Grid::make(2)->schema([
                            Textarea::make('layanan_garansi_text')->label('Garansi Layanan')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-'))->rows(2),
                            Textarea::make('layanan_pembayaran_aman')->label('Pembayaran Aman')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-'))->rows(2),
                            Textarea::make('checkout_terms_text')->label('Syarat Checkout')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-'))->rows(2),
                            Textarea::make('checkout_review_prompt')->label('Prompt Review')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-'))->rows(2),
                            Textarea::make('checkout_lengkapi_prompt')->label('Prompt Lengkapi Data')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-'))->rows(2),
                            TextInput::make('welcome_title')->label('Welcome Title')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                            Textarea::make('welcome_subtitle')->label('Welcome Subtitle')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-'))->rows(2),
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
        $defaults = (new \ReflectionClass($settings))->getDefaultProperties();
        $data = array_merge($defaults, $settings->toArray(), $data);
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
