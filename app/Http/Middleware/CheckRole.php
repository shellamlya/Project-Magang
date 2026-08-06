<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

/**
 * Middleware CheckRole
 * Memeriksa apakah user yang terautentikasi memiliki salah satu role yang diizinkan.
 */
class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu untuk mengakses halaman ini.');
        }

        $user = Auth::user();
        
        if (!$user->role || !in_array($user->role->name, $roles)) {
            // Jika role tidak diizinkan
            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut.');
            } elseif ($user->isOwner()) {
                return redirect()->route('owner.dashboard')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut.');
            }
            return redirect()->route('home')->with('error', 'Akses ditolak. Anda tidak memiliki izin.');
        }

        return $next($request);
    }
}
