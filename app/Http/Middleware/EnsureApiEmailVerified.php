<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureApiEmailVerified
{
    /**
     * Pastikan user yang mengakses API sudah memverifikasi email.
     * Mirip middleware `verified` bawaan Laravel, namun merespons JSON (untuk API).
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || ! $user->hasVerifiedEmail()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Verifikasi email diperlukan untuk mengakses fitur ini.',
            ], 403);
        }

        return $next($request);
    }
}