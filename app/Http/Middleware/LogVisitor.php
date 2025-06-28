<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use App\Models\Visitor;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogVisitor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Abaikan semua rute yang diawali dengan 'dashboard'
        if (!str($request->path())->startsWith('dashboard')) {

            $ip = $request->ip();
            $today = now()->toDateString();

            $alreadyVisited = Visitor::where('ip_address', $ip)
                ->whereDate('visited_at', $today)
                ->exists();

            if (!$alreadyVisited) {
                Visitor::create([
                    'ip_address' => $ip,
                    'user_agent' => $request->userAgent(),
                    'url' => $request->fullUrl(),
                    'visited_at' => now(),
                ]);
            }
        }

        return $next($request);
    }
}
