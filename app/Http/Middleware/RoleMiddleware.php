<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
<<<<<<< HEAD
    public function handle(Request $request, Closure $next, $role): Response
=======
    public function handle(Request $request, Closure $next, $roles): Response
>>>>>>> bf0334c2b14d316dddb6e466f2be6d6502606610
    {
        if (!auth()->check()) {
            return redirect('/login');
        }

<<<<<<< HEAD
        if (!auth()->user()->hasRole($role)) {
            abort(403, 'AKSES DITOLAK');
=======
        // Membagi string role (misal: 'admin|user') menjadi array
        $roleArray = explode('|', $roles);

        // Cek apakah user memiliki salah satu dari role tersebut
        if (!auth()->user()->hasAnyRole($roleArray)) {
            abort(403, 'AKSES DITOLAK: Anda tidak memiliki wewenang untuk mengakses halaman ini.');
>>>>>>> bf0334c2b14d316dddb6e466f2be6d6502606610
        }

        return $next($request);
    }
}