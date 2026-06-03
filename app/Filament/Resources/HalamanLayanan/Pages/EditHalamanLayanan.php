<?php

namespace App\Filament\Resources\HalamanLayanan\Pages;

use App\Filament\Resources\HalamanLayananResource;
use App\Settings\LayananSettings;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Model;

class EditHalamanLayanan extends EditRecord
{
    protected static string $resource = HalamanLayananResource::class;

    protected static ?string $title = 'Edit Halaman Layanan';

    public function mount($record = null): void
    {
        $this->record = $this->getRecord();

        $this->authorizeAccess();

        $settings = app(LayananSettings::class);
        $data = $settings->toArray();
        $defaults = (new \ReflectionClass($settings))->getDefaultProperties();
        $this->form->fill(array_merge($defaults, $data));
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Hero')
                    ->description('Atur judul, deskripsi, dan gambar latar hero halaman layanan.')
                    ->icon('heroicon-o-photo')
                    ->schema([
                        TextInput::make('layanan_hero_title')
                            ->label('Judul Hero *')
                            ->required()
                            ->helperText('Judul utama halaman layanan yang ditampilkan di bagian atas.'),
                        Textarea::make('layanan_hero_desc')
                            ->label('Deskripsi Hero')
                            ->rows(3)
                            ->helperText('Penjelasan singkat tentang layanan/paket yang ditawarkan (2-3 kalimat).'),
                        FileUpload::make('layanan_hero_image')
                            ->label('Foto Background Hero')
                            ->image()
                            ->disk('public')
                            ->directory('layanan')
                            ->helperText('Gambar latar belakang hero section. Rekomendasi: 1920x600px, max 10MB.'),
                    ]),

                Section::make('Paket Layanan')
                    ->description('Atur 4 paket layanan yang ditampilkan di halaman layanan.')
                    ->icon('heroicon-o-gift')
                    ->collapsible()
                    ->collapsed()
                    ->schema(function () {
                        $rows = [];
                        for ($i = 1; $i <= 4; $i++) {
                            $rows[] = Section::make("Paket $i")
                                ->compact()
                                ->schema([
                                    Grid::make(2)->schema([
                                        TextInput::make("layanan_{$i}_nama")
                                            ->label("Nama *")
                                            ->required()
                                            ->helperText('Nama paket layanan (contoh: Paket Basic, Premium, Eksklusif).'),
                                        TextInput::make("layanan_{$i}_harga")
                                            ->label("Harga *")
                                            ->required()
                                            ->helperText('Harga paket (contoh: Rp 1.000.000 atau Custom).'),
                                    ]),
                                    Textarea::make("layanan_{$i}_deskripsi")
                                        ->label("Deskripsi *")
                                        ->rows(2)
                                        ->required()
                                        ->columnSpanFull()
                                        ->helperText('Penjelasan singkat tentang paket ini (1-2 kalimat).'),
                                    Textarea::make("layanan_{$i}_fitur")
                                        ->label("Fitur (pisahkan per baris) *")
                                        ->rows(3)
                                        ->required()
                                        ->columnSpanFull()
                                        ->helperText('Daftar fitur paket. Setiap baris adalah satu fitur (tekan Enter untuk baris baru).'),
                                    FileUpload::make("layanan_{$i}_gambar")
                                        ->label("Gambar")
                                        ->image()
                                        ->disk('public')
                                        ->directory('layanan')
                                        ->columnSpanFull()
                                        ->helperText('Gambar untuk paket ini (opsional). Format: JPG, PNG. Max 10MB.'),
                                ]);
                        }
                        return $rows;
                    }),

                Section::make('Garansi & CTA')
                    ->description('Atur bagian garansi dan ajakan bertindak.')
                    ->icon('heroicon-o-shield-check')
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('layanan_garansi_title')
                                ->label('Judul Garansi *')
                                ->required()
                                ->helperText('Judul untuk bagian garansi (contoh: Garansi Kepuasan 100%).'),
                            TextInput::make('layanan_cta_title')
                                ->label('Judul CTA *')
                                ->required()
                                ->helperText('Judul ajakan bertindak (contoh: Siap Memilih Paket?).'),
                        ]),
                        Textarea::make('layanan_garansi_desc')
                            ->label('Deskripsi Garansi *')
                            ->rows(3)
                            ->required()
                            ->columnSpanFull()
                            ->helperText('Jelaskan jaminan atau komitmen Anda kepada pelanggan.'),
                        Textarea::make('layanan_cta_desc')
                            ->label('Deskripsi CTA *')
                            ->rows(3)
                            ->required()
                            ->columnSpanFull()
                            ->helperText('Deskripsi untuk mendorong pelanggan mengambil tindakan.'),
                    ]),
            ]);
    }

    public function save(bool $shouldRedirect = true, bool $shouldSendSavedNotification = true): void
    {
        $this->callHook('beforeValidate');

        $data = $this->form->getState();

        $this->callHook('beforeSave');

        $settings = app(LayananSettings::class);
        $defaults = (new \ReflectionClass($settings))->getDefaultProperties();
        $data = array_merge($defaults, $settings->toArray(), $data);
        foreach ($data as $key => $value) {
            $settings->{$key} = $value;
        }
        $settings->save();

        $this->callHook('afterSave');

        Notification::make()
            ->success()
            ->title('Konten halaman layanan berhasil diperbarui!')
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
                ->label('Lihat Halaman Layanan')
                ->icon('heroicon-m-arrow-top-right-on-square')
                ->color('gray')
                ->url(url('/layanan'))
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
