<?php

namespace App\Filament\Concerns;

use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;
use Spatie\LaravelSettings\Settings;

/**
 * EditSettingsPage Trait
 *
 * Menghilangkan duplikasi mount/save logic yang identik di semua
 * settings-based EditRecord pages (EditBeranda, EditCompany, EditContent, dll).
 *
 * Cara pakai:
 * 1. Tambahkan `use EditSettingsPage;` di class EditRecord
 * 2. Override `protected function getSettingsClass(): string` untuk return Settings class
 * 3. Override `protected function getSavedNotificationTitle(): string` (opsional)
 */
trait EditSettingsPage
{
    /**
     * Return kelas Settings (Spatie) yang digunakan halaman ini.
     * Contoh: return CompanySettings::class;
     */
    abstract protected function getSettingsClass(): string;

    /**
     * Judul notifikasi setelah berhasil simpan.
     */
    protected function getSavedNotificationTitle(): string
    {
        return 'Data berhasil diperbarui!';
    }

    public function mount(string|int $record = null): void
    {
        $this->record = $this->getRecord();
        $this->authorizeAccess();

        $settings = app($this->getSettingsClass());
        $data = $settings->toArray();
        $defaults = (new \ReflectionClass($settings))->getDefaultProperties();
        $this->form->fill(array_merge($defaults, $data));
    }

    public function save(bool $shouldRedirect = true, bool $shouldSendSavedNotification = true): void
    {
        $this->callHook('beforeValidate');
        $data = $this->form->getState();
        $this->callHook('beforeSave');

        $settings = app($this->getSettingsClass());
        $defaults = (new \ReflectionClass($settings))->getDefaultProperties();
        $data = array_merge($defaults, $settings->toArray(), $data);

        foreach ($data as $key => $value) {
            $settings->{$key} = $value;
        }
        $settings->save();

        $this->callHook('afterSave');

        Notification::make()
            ->success()
            ->title($this->getSavedNotificationTitle())
            ->send();
    }

    public function getRecord(): Model
    {
        return new \App\Models\DummyModel;
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
