<?php

namespace App\Filament\Resources\Pesanans\Schemas;

use App\Filament\Resources\Pesanans\Schemas\Sections\OrderDetailSection;
use App\Filament\Resources\Pesanans\Schemas\Sections\WalkInBillingSection;
use App\Filament\Resources\Pesanans\Schemas\Sections\WalkInCustomerSection;
use App\Filament\Resources\Pesanans\Schemas\Sections\WalkInPackageSection;
use App\Filament\Resources\Pesanans\Schemas\Sections\WalkInVehicleSection;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Schema;

class PesananForm
{
    public static function configure(Schema $schema): Schema
    {
        $isCreate = $schema->getOperation() === 'create';

        if ($isCreate) {
            return self::configureCreateWalkIn($schema);
        }

        return self::configureViewOrEdit($schema);
    }

    /**
     * Form Khusus Pembuatan Pesanan Walk-in / Offline
     */
    protected static function configureCreateWalkIn(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Grid::make(['default' => 1, 'lg' => 12])
                    ->columnSpanFull()
                    ->schema([
                        // Kolom Kiri (7/12): Data Pelanggan, Kendaraan & Paket Layanan
                        Group::make([
                            WalkInCustomerSection::make(),
                            WalkInVehicleSection::make(),
                            WalkInPackageSection::make(),
                        ])->columnSpan(['default' => 12, 'lg' => 7]),

                        // Kolom Kanan (5/12): Jadwal Pengerjaan, Kasir & Tagihan
                        WalkInBillingSection::make()
                            ->columnSpan(['default' => 12, 'lg' => 5]),
                    ]),
            ]);
    }

    /**
     * Form untuk Edit / View Pesanan Yang Sudah Ada
     */
    protected static function configureViewOrEdit(Schema $schema): Schema
    {
        return $schema->components(OrderDetailSection::make());
    }
}
