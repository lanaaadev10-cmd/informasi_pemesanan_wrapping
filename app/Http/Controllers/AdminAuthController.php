<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Filament\Facades\Filament;
use Filament\Models\Contracts\FilamentUser;

class AdminAuthController extends Controller
{
    /**
     * Memproses autentikasi form login Administrator secara native HTTP POST
     * agar 100% andal di seluruh browser tanpa kendala event AJAX/Livewire.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required' => 'Email administrator wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        $remember = $request->boolean('remember');

        $authGuard = Filament::auth();

        if (! $authGuard->attempt($credentials, $remember)) {
            return back()
                ->withInput($request->only('email', 'remember'))
                ->withErrors([
                    'email' => 'Email atau kata sandi administrator salah.',
                ]);
        }

        $user = $authGuard->user();
        $panel = Filament::getCurrentOrDefaultPanel();

        // Validasi akses panel admin
        if ($user instanceof FilamentUser && ! $user->canAccessPanel($panel)) {
            $authGuard->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'email' => 'Akun Anda tidak memiliki izin akses ke Panel Administrator.',
                ]);
        }

        $request->session()->regenerate();

        return redirect()->intended(url('/admin'));
    }
}
