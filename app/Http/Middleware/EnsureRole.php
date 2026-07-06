<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    /**
     * Contoh pemakaian di route: ->middleware('role:admin')
     * atau untuk lebih dari satu role: ->middleware('role:admin,member')
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user(); // pakai guard default 'web'

        if (!$user || !in_array($user->role, $roles, true)) {
            abort(403, 'Anda tidak punya akses ke halaman ini.');
        }

        if (!$user->is_active) {
            abort(403, 'Akun Anda sedang dinonaktifkan.');
        }

        return $next($request);
    }
}
