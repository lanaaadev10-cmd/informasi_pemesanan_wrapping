<?php

namespace App\Filament\Resources\BlockedDates\Pages;

use App\Filament\Resources\BlockedDates\BlockedDateResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBlockedDates extends ListRecords
{
    protected static string $resource = BlockedDateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Tambah Tanggal Libur / Blokir'),
        ];
    }
}
