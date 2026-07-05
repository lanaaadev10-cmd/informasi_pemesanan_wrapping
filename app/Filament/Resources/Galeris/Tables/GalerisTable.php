<?php

namespace App\Filament\Resources\Galeris\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class GalerisTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('foto')
                    ->label('Foto')
                    ->size(80),

                TextColumn::make('judul')
                    ->label('Judul Pekerjaan')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('tanggal_upload')
                    ->label('Tanggal Upload')
                    ->date()
                    ->sortable(),

                BadgeColumn::make('is_featured')
                    ->label('Featured')
                    ->getStateUsing(fn ($record) => $record->is_featured ? 'Ya' : 'Tidak')
                    ->colors([
                        'success' => 'Ya',
                        'gray' => 'Tidak',
                    ]),
            ])
            ->filters([
                TernaryFilter::make('is_featured')
                    ->label('Featured Only'),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
