<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Informasi Akun')
                    ->description('Data login dan hak akses pengguna.')
                    ->icon('heroicon-o-user-circle')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('name')
                                    ->label('Nama Lengkap *')
                                    ->required()
                                    ->maxLength(255)
                                    ->helperText('Nama lengkap pengguna yang akan muncul di profil.'),
                                TextInput::make('email')
                                    ->label('Alamat Email *')
                                    ->email()
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true)
                                    ->helperText('Email digunakan untuk login dan notifikasi.'),
                            ]),
                        Grid::make(2)
                            ->schema([
                                TextInput::make('password')
                                    ->label('Password *')
                                    ->password()
                                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                                    ->dehydrated(fn ($state) => filled($state))
                                    ->required(fn (string $context): bool => $context === 'create')
                                    ->helperText('Minimal 8 karakter. Kosongkan jika tidak ingin mengubah password.'),
                                Select::make('roles')
                                    ->label('Role / Hak Akses')
                                    ->multiple()
                                    ->relationship('roles', 'name')
                                    ->preload()
                                    ->helperText('Pilih hak akses yang dimiliki pengguna.'),
                            ]),
                    ]),
            ]);
    }
}
