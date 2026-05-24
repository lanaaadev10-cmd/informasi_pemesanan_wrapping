<?php

namespace App\Filament\Resources\Layanans\Schemas;

use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Get;

class LayananForm
{
    public static function schema(): array
    {
        return [
            Forms\Components\FileUpload::make('foto_contoh')
                ->label('Foto Layanan')
                ->image()
                ->disk('public')
                ->directory('layanan/admin')
                ->imagePreviewHeight(140)
                ->columnSpanFull()
                ->helperText('Ukuran rekomendasi: 1200x800px'),

            Forms\Components\TextInput::make('nama_layanan')
                ->label('Nama Layanan')
                ->required()
                ->maxLength(255)
                ->columnSpan(2)
                ->helperText('Contoh: Precision Coating - Protection Ultimate'),

            Forms\Components\Select::make('kategori')
                ->label('Kategori Kendaraan')
                ->options([
                    'mobil' => 'Mobil',
                    'motor' => 'Sepeda Motor',
                    'stiker' => 'Custom Stiker',
                ])
                ->required()
                ->columnSpan(1),

            Forms\Components\Select::make('tipe_layanan')
                ->label('Tipe Penawaran')
                ->options([
                    'fix'    => 'Fixed Price (Harga Tetap)',
                    'custom' => 'Custom Quote (Negosiasi)',
                ])
                ->required()
                ->columnSpan(1)
                ->live(),

            Forms\Components\TextInput::make('harga')
                ->label('Harga Paket (Rp)')
                ->numeric()
                ->prefix('Rp ')
                ->required()
                ->columnSpan(1)
                ->visible(fn (Get $get) => $get('tipe_layanan') === 'fix'),

            Forms\Components\TextInput::make('estimasi_waktu')
                ->label('Estimasi Waktu Pengerjaan')
                ->placeholder('Contoh: 3 hari kerja')
                ->columnSpan(1)
                ->helperText('Durasi pengerjaan standar'),

            Forms\Components\RichEditor::make('deskripsi')
                ->label('Deskripsi Lengkap Paket')
                ->toolbarButtons([
                    'bold',
                    'italic',
                    'strike',
                    'link',
                    'bulletList',
                    'orderedList',
                    'redo',
                    'undo',
                ])
                ->columnSpanFull()
                ->helperText('Jelaskan detail, manfaat, dan spesifikasi paket ini'),

            Forms\Components\Repeater::make('fitur')
                ->label('Daftar Keunggulan & Fitur Paket')
                ->schema([
                    Forms\Components\TextInput::make('nama_fitur')
                        ->label('Keunggulan')
                        ->placeholder('Contoh: Garansi Resmi hingga 5 Tahun')
                        ->required()
                        ->columnSpanFull(),
                ])
                ->columnSpanFull()
                ->columns(1)
                ->addActionLabel('+ Tambah Keunggulan Baru')
                ->defaultItems(0)
                ->minItems(1)
                ->helperText('Tambahkan fitur-fitur unggulan yang menjadi keunggulan paket ini. Minimal 1 keunggulan harus ditambahkan.'),
        ];
    }
}