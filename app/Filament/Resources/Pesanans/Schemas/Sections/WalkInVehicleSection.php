<?php

namespace App\Filament\Resources\Pesanans\Schemas\Sections;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;

class WalkInVehicleSection
{
    public static function make(): Section
    {
        return Section::make('Data Kendaraan Pelanggan')
            ->icon('heroicon-o-truck')
            ->description('Identitas mobil yang akan dikerjakan di workshop.')
            ->schema([
                Grid::make(2)
                    ->schema([
                        TextInput::make('model_kendaraan')
                            ->label('Merk & Model Mobil *')
                            ->placeholder('Contoh: Honda Civic Turbo 2023')
                            ->required(),

                        TextInput::make('nomor_polisi')
                            ->label('Nomor Polisi (Plat) *')
                            ->placeholder('Contoh: B 1234 ABC')
                            ->required(),

                        TextInput::make('warna_kendaraan')
                            ->label('Warna Asli Mobil')
                            ->placeholder('Contoh: Hitam Glossy'),

                        TextInput::make('tahun_produksi')
                            ->label('Tahun Perakitan')
                            ->numeric()
                            ->placeholder('2023'),
                    ]),

                Textarea::make('catatan_admin')
                    ->label('Catatan Pengerjaan / Permintaan Khusus')
                    ->placeholder('Catatan bagian yang di-wrap (full body / atap / kap mesin), jenis & warna stiker, kondisi fisik awal kendaraan, dll.')
                    ->rows(3),
            ]);
    }
}
