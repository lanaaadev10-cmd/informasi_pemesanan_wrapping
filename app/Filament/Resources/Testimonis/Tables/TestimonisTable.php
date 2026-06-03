<?php

namespace App\Filament\Resources\Testimonis\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Table;

class TestimonisTable
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

                TextColumn::make('isi_testimoni')
                    ->label('Testimoni')
                    ->limit(80)
                    ->searchable(),

                TextColumn::make('rating')
                    ->label('Rating')
                    ->badge()
                    ->color(fn (int $state): string => match ($state) {
                        5 => 'success',
                        4 => 'info',
                        3 => 'warning',
                        default => 'danger',
                    }),

                BadgeColumn::make('is_tampilkan')
                    ->label('Tampil')
                    ->getStateUsing(fn ($record) => $record->is_tampilkan ? 'Ya' : 'Tidak')
                    ->colors([
                        'success' => 'Ya',
                        'gray' => 'Tidak',
                    ]),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
