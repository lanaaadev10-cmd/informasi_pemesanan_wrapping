<?php

namespace App\Filament\Http\Middleware;

use Filament\Facades\Filament;
use Filament\Http\Middleware\Authenticate as BaseAuthenticate;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Model;

class AdminAuthenticate extends BaseAuthenticate
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  array<string>  $guards
     */
    protected function authenticate($request, array $guards): void
    {
        $guard = Filament::auth();

        if (! $guard->check()) {
            $this->unauthenticated($request, $guards);
            return;
        }

        $this->auth->shouldUse(Filament::getAuthGuard());

        /** @var Model $user */
        $user = $guard->user();
        $panel = Filament::getCurrentOrDefaultPanel();

        // Jika user yang sedang login bukan admin, logout dan alihkan ke form login admin
        if ($user instanceof FilamentUser && ! $user->canAccessPanel($panel)) {
            $guard->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            abort(redirect()->route('filament.admin.auth.login'));
        }

        abort_if(
            $user instanceof FilamentUser ?
                (! $user->canAccessPanel($panel)) :
                (config('app.env') !== 'local'),
            403,
        );
    }
}
