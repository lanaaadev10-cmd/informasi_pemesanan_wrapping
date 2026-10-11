<?php

namespace App\Filament\Resources\VehicleBrands\Pages;

use App\Filament\Resources\VehicleBrands\VehicleBrandResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListVehicleBrands extends ListRecords
{
    protected static string $resource = VehicleBrandResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
