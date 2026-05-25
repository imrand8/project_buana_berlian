<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role): Response
    {
        // Cek apakah user sudah login dan role-nya sesuai
        if (Auth::check() && Auth::user()->role === $role) {
            return $next($request);
        }

        // Kalau pelanggan iseng buka halaman admin, tendang ke halaman home!
        return redirect('/')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
    }
}