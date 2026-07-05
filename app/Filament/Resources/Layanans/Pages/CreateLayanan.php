<?php

namespace App\Filament\Resources\Layanans\Pages;

use App\Filament\Resources\Layanans\LayananResource;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\Layanans\Schemas\LayananForm;

class CreateLayanan extends CreateRecord
{
    protected static string $resource = LayananResource::class;

    protected function getFormSchema(): array
    {
        return [
            LayananForm::schema(),
        ];
        return LayananForm::schema();
    }
}