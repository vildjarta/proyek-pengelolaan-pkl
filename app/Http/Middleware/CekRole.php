<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CekRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  ...$roles  // Kita akan terima daftar peran yang diizinkan
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Cek apakah user sudah login DAN perannya ada di dalam daftar $roles
        if (!Auth::check() || !in_array(Auth::user()->role, $roles)) {
            // Jika tidak diizinkan, kembalikan ke halaman home
            // atau tampilkan halaman 403 (Forbidden)
            return redirect('/home')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}