<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role): Response
    {
        // 1. Cek apakah user sudah login?
        if (!Auth::check()) {
            return redirect('/login');
        }

        // 2. Cek apakah role user sesuai dengan yang diminta?
        if (Auth::user()->role !== $role) {
            // Kalau role beda, tendang balik (403 Forbidden)
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}