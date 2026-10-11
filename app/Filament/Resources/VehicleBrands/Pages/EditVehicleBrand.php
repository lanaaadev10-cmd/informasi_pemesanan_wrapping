<?php

namespace App\Filament\Resources\VehicleBrands\Pages;

use App\Filament\Resources\VehicleBrands\VehicleBrandResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditVehicleBrand extends EditRecord
{
    protected static string $resource = VehicleBrandResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
