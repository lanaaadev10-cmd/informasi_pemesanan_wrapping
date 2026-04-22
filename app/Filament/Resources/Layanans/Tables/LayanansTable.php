<?php

namespace App\Filament\Resources\Layanans\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class LayanansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id_layanan')
                    ->label('ID')
                    ->sortable(),

                TextColumn::make('nama_layanan')
                    ->label('nama_layanan')
                    ->searchable(),

                TextColumn::make('tipe_layanan')
                    ->label('Tipe')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'fix'    => 'success',
                        'custom' => 'warning',
                        default  => 'gray',
                    }),

                TextColumn::make('harga')
                    ->label('Harga')
                    ->money('IDR')
                    ->sortable(),

                TextColumn::make('estimasi_waktu')
                    ->label('Estimasi'),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('tipe_layanan')
                    ->label('Tipe Layanan')
                    ->options([
                        'fix'    => 'Fix',
                        'custom' => 'Custom',
                    ]),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}