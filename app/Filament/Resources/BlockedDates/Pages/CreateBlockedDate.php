<?php

namespace App\Filament\Resources\BlockedDates\Pages;

use App\Filament\Resources\BlockedDates\BlockedDateResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBlockedDate extends CreateRecord
{
    protected static string $resource = BlockedDateResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = auth()->id();
        return $data;
    }
}
