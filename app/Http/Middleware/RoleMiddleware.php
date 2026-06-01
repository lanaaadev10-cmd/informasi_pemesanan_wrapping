<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $roles): Response
    {
        if (!auth()->check()) {
            return redirect('/login');
        }

        // Membagi string role (misal: 'admin|user') menjadi array
        $roleArray = explode('|', $roles);

        // Cek apakah user memiliki salah satu dari role tersebut
        if (!auth()->user()->hasAnyRole($roleArray)) {
            abort(403, 'AKSES DITOLAK: Anda tidak memiliki wewenang untuk mengakses halaman ini.');
        }

        return $next($request);
    }
}