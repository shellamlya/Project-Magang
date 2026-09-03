<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\WebsiteVisitor;
use Illuminate\Support\Facades\Log;

class TrackWebsiteVisitor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Hanya proses request GET yang berhasil (status 200 OK)
        if ($request->method() !== 'GET' || $response->getStatusCode() !== 200) {
            return $response;
        }

        // Jangan hitung AJAX, JSON, atau request non-HTML
        if ($request->ajax() || $request->wantsJson() || $request->isXmlHttpRequest()) {
            return $response;
        }

        // Jangan hitung area Admin, Owner, API, debug, atau assets
        if ($request->is('admin*', 'owner*', 'api*', 'up', '_debugbar*', 'sanctum*')) {
            return $response;
        }

        // Jangan hitung jika yang sedang login adalah Admin atau Owner
        if (auth()->check()) {
            $user = auth()->user();
            if ($user->isAdmin() || $user->isOwner()) {
                return $response;
            }
        }

        try {
            if ($request->hasSession()) {
                $sessionId = $request->session()->getId();

                if (!empty($sessionId)) {
                    $visitor = WebsiteVisitor::where('session_id', $sessionId)->first();

                    if (!$visitor) {
                        WebsiteVisitor::create([
                            'session_id' => $sessionId,
                            'ip_address' => $request->ip(),
                            'user_agent' => substr((string) $request->userAgent(), 0, 500),
                            'page_views' => 1,
                        ]);
                    } else {
                        // Jika session sudah ada, hanya tambahkan counter page_views tanpa menambah row pengunjung baru
                        $visitor->increment('page_views');
                    }
                }
            }
        } catch (\Throwable $e) {
            // Jangan gagalkan response jika ada error logging visitor
            Log::warning('TrackWebsiteVisitor error: ' . $e->getMessage());
        }

        return $response;
    }
}
