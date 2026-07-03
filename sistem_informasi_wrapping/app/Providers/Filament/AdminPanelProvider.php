<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

/*
 * ============================================
 *  PANEL PROVIDER FILAMENT — Admin Panel
 * ============================================
 * File ini mengkonfigurasi panel admin Filament yang digunakan
 * oleh staff/admin untuk mengelola konten, pesanan, & laporan.
 *
 * Panel diakses via URL: /admin
 * Hanya user dengan role 'admin' yang bisa mengakses.
 * Panel ini menggunakan sistem autentikasi terpisah dari
 * dashboard customer (meskipun sama-sama pakai guard 'web').
 */

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()                             // Panel default (utama)
            ->id('admin')                           // ID internal panel
            ->path('admin')                         // URL prefix: /admin
            ->login()                               // Pakai halaman login bawaan Filament
            ->colors([
                'primary' => Color::Orange,         // Tema warna oranye
            ])
            ->databaseNotifications()                // Notifikasi via database table

            // ========================================
            //  NAVIGASI SIDEBAR — Grup menu
            // ========================================
            // Setiap grup akan muncul di sidebar kiri panel admin.
            // Resource/Page akan masuk ke grup sesuai dengan
            // deklarasi $navigationGroup di masing-masing class.
            ->navigationGroups([
                NavigationGroup::make('Kelola Landing Page')
                    ->icon('heroicon-o-globe-alt')->collapsed(false),

                NavigationGroup::make('Kelola User Dashboard')
                    ->icon('heroicon-o-computer-desktop')->collapsed(false),

                NavigationGroup::make('Laporan & Pembayaran')
                    ->icon('heroicon-o-presentation-chart-line')->collapsed(false),

                NavigationGroup::make('Manajemen Konten')
                    ->icon('heroicon-o-square-3-stack-3d')->collapsed(false),

                NavigationGroup::make('Manajemen Tim & Perusahaan')
                    ->icon('heroicon-o-users')->collapsed(false),

                NavigationGroup::make('Pengaturan Sistem')
                    ->icon('heroicon-o-cog-6-tooth')->collapsed(true),
            ])

            // Auto-discovery: Filamen akan scan folder berikut
            // untuk mendaftarkan Resource, Page, dan Widget secara otomatis
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,                   // Dashboard utama Filament
            ])
            ->brandName('Admin Wrapping')

            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,               // Widget profil user
                FilamentInfoWidget::class,           // Info framework
            ])

            // Middleware untuk semua request ke /admin
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])

            // Middleware autentikasi khusus Filament
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
