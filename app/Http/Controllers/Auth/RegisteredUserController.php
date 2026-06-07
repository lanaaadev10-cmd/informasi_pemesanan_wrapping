<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:255', 'not_regex:/[0-9]/'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => [
                'required',
                'confirmed',
                'min:9',
                'max:20',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*()_+\-=\[\]{};\':"\\|,.<>\/?]).{9,20}$/',
            ],
            'phone' => [
                'nullable',
                'string',
                'regex:/^(\+62|62|0)8[1-9][0-9]{7,10}$/',
            ],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'name.min' => 'Nama minimal :min karakter.',
            'name.not_regex' => 'Nama tidak boleh mengandung angka.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid. Contoh: nama@email.com',
            'email.unique' => 'Email ini sudah terdaftar, silakan gunakan email lain.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.confirmed' => 'Kata sandi tidak cocok, silakan periksa kembali.',
            'password.min' => 'Kata sandi minimal :min karakter.',
            'password.max' => 'Kata sandi maksimal :max karakter.',
            'password.regex' => 'Kata sandi harus mengandung minimal 1 huruf kapital, 1 huruf kecil, dan 1 simbol.',
            'phone.regex' => 'Format nomor tidak valid. Gunakan format: 08xxxxxxxxxx',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
        ]);

        // [OTOMATIS] Berikan role 'user' kepada pendaftar baru (pastikan role ada)
        Role::firstOrCreate([
            'name' => 'user',
            'guard_name' => 'web',
        ]);

        $user->assignRole('user');

        event(new Registered($user));

        Auth::guard('web')->login($user);
        $request->session()->regenerate();

        return redirect(route('dashboard', absolute: false));
    }
}
