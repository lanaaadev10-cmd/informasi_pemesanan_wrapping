<?php

namespace App\Filament\Auth;

use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Auth\Http\Responses\Contracts\LoginResponse;
use Filament\Facades\Filament;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\ValidationException;

/**
 * Custom Filament Admin Login Page
 * 
 * Mengimplementasikan tata letak modern split-card sesuai referensi:
 * - Sisi kiri: Solid/gradient orange dengan teks putih kontras
 * - Pembatas: S-curve wave organik SVG
 * - Sisi kanan: Form login pill-shaped inputs berkelas
 * - Warna & Tipografi: Mengikuti token sistem (racing-orange, Audiowide, Montserrat, Questrial)
 */
class Login extends BaseLogin
{
    protected string $view = 'filament.auth.login';
    protected static string $layout = 'filament.auth.layout';

    public ?array $data = [
        'email' => '',
        'password' => '',
        'remember' => false,
    ];

    public function mount(): void
    {
        if (Filament::auth()->check()) {
            $user = Filament::auth()->user();
            $panel = Filament::getCurrentOrDefaultPanel();

            if ($user instanceof FilamentUser && $user->canAccessPanel($panel)) {
                redirect()->intended(Filament::getUrl());
                return;
            }

            // Jika user biasa yang login, logout agar form login admin bisa digunakan
            Filament::auth()->logout();
            session()->regenerateToken();
        }

        $this->data = [
            'email' => '',
            'password' => '',
            'remember' => false,
        ];
    }

    public function authenticate(): ?LoginResponse
    {
        $this->validate([
            'data.email' => 'required|email',
            'data.password' => 'required',
        ], [
            'data.email.required' => 'Email administrator wajib diisi.',
            'data.email.email' => 'Format email tidak valid.',
            'data.password.required' => 'Kata sandi wajib diisi.',
        ]);

        $credentials = [
            'email' => trim($this->data['email']),
            'password' => $this->data['password'],
        ];

        $remember = (bool) ($this->data['remember'] ?? false);

        $authGuard = Filament::auth();

        if (! $authGuard->attempt($credentials, $remember)) {
            throw ValidationException::withMessages([
                'data.email' => 'Email atau kata sandi administrator salah.',
            ]);
        }

        $user = $authGuard->user();

        if ($user instanceof FilamentUser && ! $user->canAccessPanel(Filament::getCurrentOrDefaultPanel())) {
            $authGuard->logout();
            throw ValidationException::withMessages([
                'data.email' => 'Akun Anda tidak memiliki izin akses ke Panel Administrator.',
            ]);
        }

        session()->regenerate();

        $redirectUrl = session()->pull('url.intended', url('/admin'));

        $this->redirect($redirectUrl, navigate: false);

        return app(LoginResponse::class);
    }

    public function render(): View
    {
        return view($this->view, $this->getViewData())
            ->layout(static::$layout, [
                'livewire' => $this,
                ...$this->getLayoutData(),
            ]);
    }
}
