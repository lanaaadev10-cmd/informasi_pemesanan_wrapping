<?php

namespace App\Filament\Resources\Ratings\Pages;

use App\Filament\Resources\Ratings\RatingResource;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewRating extends ViewRecord
{
    protected static string $resource = RatingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('balas')
                ->label(fn (): string => $this->record->balasan_admin ? 'Ubah Balasan' : 'Balas')
                ->icon('heroicon-m-chat-bubble-left-ellipsis')
                ->color('warning')
                ->fillForm(fn (): array => [
                    'balasan_admin' => $this->record->balasan_admin,
                ])
                ->form([
                    Textarea::make('balasan_admin')
                        ->label('Balasan Admin')
                        ->placeholder('Tulis balasan untuk testimoni pelanggan...')
                        ->rows(5)
                        ->maxLength(1000)
                        ->required(),
                ])
                ->action(function (array $data): void {
                    $this->record->update([
                        'balasan_admin' => $data['balasan_admin'],
                        'dibalas_at' => now(),
                    ]);

                    Notification::make()
                        ->title('Balasan disimpan')
                        ->success()
                        ->send();
                }),
        ];
    }
}