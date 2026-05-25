<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
<<<<<<< HEAD
=======
use Spatie\Permission\Models\Role;
>>>>>>> bf0334c2b14d316dddb6e466f2be6d6502606610
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

<<<<<<< HEAD
        event(new Registered($user));

        Auth::login($user);
=======
        // [OTOMATIS] Berikan role 'user' kepada pendaftar baru (pastikan role ada)
        Role::firstOrCreate([
            'name' => 'user',
            'guard_name' => 'web',
        ]);

        $user->assignRole('user');

        event(new Registered($user));

        Auth::guard('web')->login($user);
        $request->session()->regenerate();
>>>>>>> bf0334c2b14d316dddb6e466f2be6d6502606610

        return redirect(route('dashboard', absolute: false));
    }
}
