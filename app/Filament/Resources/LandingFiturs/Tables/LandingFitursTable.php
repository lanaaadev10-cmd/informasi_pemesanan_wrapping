<?php

namespace App\Filament\Resources\LandingFiturs\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Table;

class LandingFitursTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('gambar')
                    ->label('Gambar')
                    ->size(60),

                TextColumn::make('judul')
                    ->label('Judul')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('deskripsi')
                    ->label('Deskripsi')
                    ->limit(100),

                TextColumn::make('ikon')
                    ->label('Ikon'),

                TextColumn::make('urutan')
                    ->label('Urutan')
                    ->sortable(),

                BadgeColumn::make('is_aktif')
                    ->label('Aktif')
                    ->getStateUsing(fn ($record) => $record->is_aktif ? 'Ya' : 'Tidak')
                    ->colors([
                        'success' => 'Ya',
                        'gray' => 'Tidak',
                    ]),
            ])
            ->defaultSort('urutan', 'asc')
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
