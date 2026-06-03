<?php

namespace App\Filament\Resources\TeamMembers\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Table;

class TeamMembersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('foto')
                    ->label('Foto')
                    ->size(60),

                TextColumn::make('nama')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('jabatan')
                    ->label('Jabatan'),

                BadgeColumn::make('is_aktif')
                    ->label('Aktif')
                    ->getStateUsing(fn ($record) => $record->is_aktif ? 'Ya' : 'Tidak')
                    ->colors([
                        'success' => 'Ya',
                        'gray' => 'Tidak',
                    ]),

                TextColumn::make('urutan')
                    ->label('Urutan')
                    ->sortable(),
            ])
            ->defaultSort('urutan', 'asc')
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
