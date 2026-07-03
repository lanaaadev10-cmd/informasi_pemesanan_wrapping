<?php

namespace App\Filament\Resources\TeamMembers\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TeamMemberForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Profil Anggota Tim')
                    ->description('Nama, jabatan, bio, dan foto anggota tim.')
                    ->icon('heroicon-o-user')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('nama')
                                    ->label('Nama Lengkap *')
                                    ->required()
                                    ->maxLength(255)
                                    ->helperText('Nama lengkap anggota tim.'),
                                TextInput::make('jabatan')
                                    ->label('Jabatan')
                                    ->maxLength(255)
                                    ->helperText('Contoh: CEO, Designer.'),
                            ]),
                        Textarea::make('bio')
                            ->label('Bio')
                            ->rows(4)
                            ->helperText('Deskripsi singkat tentang anggota tim.'),
                        FileUpload::make('foto')
                            ->label('Foto')
                            ->image()
                            ->disk('public')
                            ->directory('team-members')
                            ->imagePreviewHeight(140)
                            ->helperText('Upload foto anggota tim. Ukuran rekomendasi: 400x400px.'),
                    ]),

                Section::make('Media Sosial')
                    ->description('Link profil media sosial anggota tim.')
                    ->icon('heroicon-o-share')
                    ->collapsible()
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('instagram')
                                    ->label('Instagram')
                                    ->maxLength(255)
                                    ->helperText('URL profil Instagram.'),
                                TextInput::make('linkedin')
                                    ->label('LinkedIn')
                                    ->maxLength(255)
                                    ->helperText('URL profil LinkedIn.'),
                            ]),
                    ]),

                Section::make('Pengaturan Tampilan')
                    ->description('Urutan tampilan dan status aktif.')
                    ->icon('heroicon-o-cog-6-tooth')
                    ->collapsible()
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('urutan')
                                    ->label('Urutan')
                                    ->numeric()
                                    ->default(0)
                                    ->helperText('Nomor urut tampilan (semakin kecil semakin atas).'),
                                Toggle::make('is_aktif')
                                    ->label('Aktif?')
                                    ->default(true)
                                    ->helperText('Jika aktif, anggota tim akan ditampilkan di halaman website.'),
                            ]),
                    ]),
            ]);
    }
}
