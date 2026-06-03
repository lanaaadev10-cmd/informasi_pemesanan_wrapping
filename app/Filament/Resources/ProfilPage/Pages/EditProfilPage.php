<?php

namespace App\Filament\Resources\ProfilPage\Pages;

use App\Filament\Resources\ProfilPageResource;
use App\Settings\ProfilPageSettings;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Model;

class EditProfilPage extends EditRecord
{
    protected static string $resource = ProfilPageResource::class;

    protected static ?string $title = 'Edit Halaman Profil Perusahaan';

    public function mount($record = null): void
    {
        $this->record = $this->getRecord();
        $this->authorizeAccess();
        $settings = app(ProfilPageSettings::class);
        $data = $settings->toArray();
        $defaults = (new \ReflectionClass($settings))->getDefaultProperties();
        $this->form->fill(array_merge($defaults, $data));
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('SEO & Meta')
                    ->icon('heroicon-o-globe-alt')
                    ->schema([
                        TextInput::make('meta_title')->label('Meta Title')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                        Textarea::make('meta_description')->label('Meta Description')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-'))->rows(2),
                        TextInput::make('stats_projects')->label('Jumlah Proyek (Statistik)')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                    ]),

                Section::make('Hero & Banner')
                    ->icon('heroicon-o-photo')
                    ->schema([
                        TextInput::make('profil_global_badge')->label('Badge Global')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                        TextInput::make('profil_global_title')->label('Judul Global')->required()->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                        Textarea::make('profil_global_desc')->label('Deskripsi Global')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-'))->rows(3),
                        TextInput::make('profil_banner_heading')->label('Heading Banner')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                    ]),

                Section::make('Section Narrative')
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        TextInput::make('profil_section_narrative_badge')->label('Badge Narrative')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                        TextInput::make('profil_section_narrative_title')->label('Judul Narrative')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                        TextInput::make('profil_stat_label')->label('Label Statistik')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                        Textarea::make('profil_stat_desc')->label('Deskripsi Statistik')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                    ]),

                Section::make('Pillars')
                    ->description('Pilar-pilar perusahaan')
                    ->icon('heroicon-o-squares-2x2')
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        Grid::make(3)->schema([
                            TextInput::make('profil_pillar_1_title')->label('Pilar 1 - Judul')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                            TextInput::make('profil_pillar_1_desc')->label('Pilar 1 - Deskripsi')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                            TextInput::make('profil_pillar_2_title')->label('Pilar 2 - Judul')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                            TextInput::make('profil_pillar_2_desc')->label('Pilar 2 - Deskripsi')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                            TextInput::make('profil_pillar_3_title')->label('Pilar 3 - Judul')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                            TextInput::make('profil_pillar_3_desc')->label('Pilar 3 - Deskripsi')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                        ]),
                    ]),

                Section::make('Section Info')
                    ->icon('heroicon-o-information-circle')
                    ->schema([
                        TextInput::make('profil_section_badge')->label('Badge Section')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                        TextInput::make('profil_section_title')->label('Judul Section')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                        TextInput::make('profil_pillar_section_title')->label('Judul Section Pillar')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                    ]),

                Section::make('CTA & Legal')
                    ->icon('heroicon-o-scale')
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        TextInput::make('profil_cta_button')->label('Tombol CTA')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                        TextInput::make('profil_legal_title_modal')->label('Judul Modal Legal')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                        TextInput::make('profil_legal_visi_title')->label('Judul Visi (Legal)')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                        TextInput::make('profil_legal_misi_title')->label('Judul Misi (Legal)')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                        TextInput::make('profil_legal_sejarah_title')->label('Judul Sejarah (Legal)')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                    ]),
            ]);
    }

    public function save(bool $shouldRedirect = true, bool $shouldSendSavedNotification = true): void
    {
        $this->callHook('beforeValidate');
        $data = $this->form->getState();
        $this->callHook('beforeSave');

        $settings = app(ProfilPageSettings::class);
        $defaults = (new \ReflectionClass($settings))->getDefaultProperties();
        $data = array_merge($defaults, $settings->toArray(), $data);
        foreach ($data as $key => $value) {
            $settings->{$key} = $value;
        }
        $settings->save();
        $this->callHook('afterSave');

        Notification::make()
            ->success()
            ->title('Halaman Profil berhasil diperbarui!')
            ->send();
    }

    public function getRecord(): Model
    {
        return new \App\Models\DummyModel;
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('view_website')
                ->label('Lihat Website')
                ->icon('heroicon-m-arrow-top-right-on-square')
                ->color('gray')
                ->url(url('/profil-perusahaan'))
                ->openUrlInNewTab(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function authorizeAccess(): void
    {
        abort_unless(static::getResource()::canViewAny(), 403);
    }
}
