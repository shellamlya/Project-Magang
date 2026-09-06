<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class EnsureOwnerApproved
{
    /**
     * Handle an incoming request.
     *
     * Memastikan akun Owner telah disetujui (approved) oleh Admin sebelum dapat
     * mengakses dashboard dan fitur pengajuan tempat usaha.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Jika belum login, biarkan auth middleware yang menangani atau arahkan ke login
        if (!$user) {
            return redirect()->route('login');
        }

        // Jika user adalah Admin, izinkan akses penuh
        if ($user->isAdmin()) {
            return $next($request);
        }

        // Cek profil owner
        $owner = $user->owner;
        if (!$owner) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Profil Owner tidak ditemukan. Silakan hubungi admin.');
        }

        $status = $owner->verification_status;

        // Jika status masih 'pending', arahkan ke halaman pending-verification
        if ($status === 'pending') {
            if (!$request->routeIs('owner.pending-verification')) {
                return redirect()->route('owner.pending-verification');
            }
            return $next($request);
        }

        // Jika status 'rejected', arahkan ke halaman rejected-verification (kecuali saat memperbarui profil/KTP)
        if ($status === 'rejected') {
            if (!$request->routeIs('owner.rejected-verification') && !$request->routeIs('owner.profile*')) {
                return redirect()->route('owner.rejected-verification');
            }
            return $next($request);
        }

        // Jika status 'approved' namun mencoba membuka halaman pending/rejected
        if ($status === 'approved') {
            if ($request->routeIs('owner.pending-verification') || $request->routeIs('owner.rejected-verification')) {
                return redirect()->route('owner.dashboard')->with('success', 'Akun Anda telah disetujui. Selamat datang di Dashboard Owner!');
            }
            return $next($request);
        }

        return $next($request);
    }
}
