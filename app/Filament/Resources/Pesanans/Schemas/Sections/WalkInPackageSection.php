<?php

namespace App\Filament\Resources\Pesanans\Schemas\Sections;

use App\Models\Layanan;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;

class WalkInPackageSection
{
    public static function make(): Section
    {
        return Section::make('Paket Layanan Wrapping')
            ->icon('heroicon-o-swatch')
            ->description('Pilih paket wrapping yang diinginkan pelanggan.')
            ->schema([
                Grid::make(2)
                    ->schema([
                        Select::make('layanan_id')
                            ->label('Pilih Paket Layanan *')
                            ->options(Layanan::pluck('nama_layanan', 'id_layanan'))
                            ->searchable()
                            ->preload()
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($state, Set $set) {
                                if ($state) {
                                    $layanan = Layanan::find($state);
                                    if ($layanan) {
                                        $set('total_harga', $layanan->harga);
                                    }
                                }
                            })
                            ->helperText('Pilih paket dari katalog. Harga akan otomatis terisi.'),

                        TextInput::make('total_harga')
                            ->label('Total Biaya (Rp) *')
                            ->numeric()
                            ->prefix('Rp')
                            ->required()
                            ->live()
                            ->helperText('Dapat disesuaikan jika ada diskon atau kesepakatan harga kasir.'),
                    ]),
            ]);
    }
}
