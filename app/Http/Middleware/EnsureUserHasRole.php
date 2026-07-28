<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user || ! in_array($user->role->value, $roles, true)) {
            if ($user) {
                return redirect()->route($user->getDashboardRouteName())
                    ->with('error', 'Akses dibatasi: Anda otomatis diarahkan ke dashboard role Anda.');
            }

            return redirect()->route('login');
        }

        return $next($request);
    }
}
