<?php

namespace App\Filament\Resources\Layanans\Schemas;

use Filament\Forms;
use Filament\Schemas\Schema;

class LayananForm
{
    public static function schema(): array
    {
        return [
            Forms\Components\TextInput::make('nama_layanan')
                ->label('Nama_layanan')
                ->required()
                ->maxLength(255),

            Forms\Components\Select::make('tipe_layanan')
                ->label('Tipe Layanan')
                ->options([
                    'fix'    => 'Fix (Harga Tetap)',
                    'custom' => 'Custom (Nego)',
                ])
                ->required(),

            Forms\Components\TextInput::make('harga')
                ->label('Harga (Rp)')
                ->numeric()
                ->prefix('Rp')
                ->required(),

            Forms\Components\TextInput::make('estimasi_waktu')
                ->label('Estimasi Waktu')
                ->placeholder('Contoh: 3 hari'),

            Forms\Components\Textarea::make('deskripsi')
                ->label('Deskripsi')
                ->columnSpanFull(),
        ];
    }
}