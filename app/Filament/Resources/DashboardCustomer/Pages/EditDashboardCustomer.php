<?php

namespace App\Filament\Resources\DashboardCustomer\Pages;

use App\Filament\Resources\DashboardCustomerResource;
use App\Settings\DashboardCustomerSettings;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Model;

class EditDashboardCustomer extends EditRecord
{
    protected static string $resource = DashboardCustomerResource::class;

    protected static ?string $title = 'Edit Dashboard Customer';

    public function mount($record = null): void
    {
        $this->record = $this->getRecord();
        $this->authorizeAccess();
        $settings = app(DashboardCustomerSettings::class);
        $data = $settings->toArray();
        $defaults = (new \ReflectionClass($settings))->getDefaultProperties();
        $this->form->fill(array_merge($defaults, $data));
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Member Card')
                    ->schema([
                        TextInput::make('dashboard_member_title')->label('Title')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                        TextInput::make('dashboard_member_desc')->label('Description')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                        TextInput::make('dashboard_member_progress')->label('Progress Label')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                        Textarea::make('dashboard_member_benefits')->label('Benefits')->rows(2)->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                    ]),

                Section::make('Quick Services')
                    ->collapsible()
                    ->collapsed()
                    ->schema(function () {
                        $rows = [];
                        for ($i = 1; $i <= 4; $i++) {
                            $rows[] = Grid::make(3)->schema([
                                TextInput::make("dashboard_service_{$i}_title")
                                    ->label("Service $i Title")
                                    ->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                                TextInput::make("dashboard_service_{$i}_desc")
                                    ->label("Service $i Description")
                                    ->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                                TextInput::make("dashboard_service_{$i}_icon")
                                    ->label("Service $i Icon")
                                    ->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                            ]);
                        }
                        return $rows;
                    }),

                Section::make('Section Labels')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('dashboard_quick_services_title')->label('Quick Services Title')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                            TextInput::make('dashboard_activity_title')->label('Activity Title')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                            TextInput::make('dashboard_section_paket_title')->label('Section Paket Title')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                            TextInput::make('dashboard_section_aktivitas_title')->label('Section Aktivitas Title')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                        ]),
                    ]),

                Section::make('Status Labels')
                    ->schema([
                        Grid::make(3)->schema([
                            TextInput::make('dashboard_status_menunggu_label')->label('Menunggu Label')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                            TextInput::make('dashboard_status_diproses_label')->label('Diproses Label')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                            TextInput::make('dashboard_status_selesai_label')->label('Selesai Label')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                        ]),
                    ]),

                Section::make('Empty States')
                    ->schema([
                        TextInput::make('dashboard_empty_title')->label('Empty Title')->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                        Textarea::make('dashboard_empty_desc')->label('Empty Description')->rows(2)->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                        Textarea::make('dashboard_empty_state_desc')->label('Empty State Description')->rows(2)->helperText(fn ($state) => 'Saat ini: ' . ($state ?: '-')),
                    ]),
            ]);
    }

    public function save(bool $shouldRedirect = true, bool $shouldSendSavedNotification = true): void
    {
        $this->callHook('beforeValidate');
        $data = $this->form->getState();
        $this->callHook('beforeSave');

        $settings = app(DashboardCustomerSettings::class);
        $defaults = (new \ReflectionClass($settings))->getDefaultProperties();
        $data = array_merge($defaults, $settings->toArray(), $data);
        foreach ($data as $key => $value) {
            $settings->{$key} = $value;
        }
        $settings->save();
        $this->callHook('afterSave');

        Notification::make()
            ->success()
            ->title('Dashboard Customer berhasil diperbarui!')
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
                ->url(route('dashboard'))
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
