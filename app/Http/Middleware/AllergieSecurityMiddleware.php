<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AllergieSecurityMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Log alle allergie-gerelateerde acties voor audit trail
        if (str_contains($request->path(), 'allergieen')) {
            Log::info('Allergie module access', [
                'method' => $request->method(),
                'path' => $request->path(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'user_id' => auth()->id(),
                'timestamp' => now(),
                'params' => $request->except(['password', '_token'])
            ]);
        }

        // Extra validatie voor PUT/POST requests
        if (in_array($request->method(), ['PUT', 'POST', 'PATCH', 'DELETE'])) {
            // Rate limiting - max 10 wijzigingen per minuut per gebruiker
            $key = 'allergie_changes_' . auth()->id();
            $attempts = cache()->get($key, 0);
            
            if ($attempts >= 10) {
                Log::warning('Rate limit exceeded for allergie changes', [
                    'user_id' => auth()->id(),
                    'ip' => $request->ip(),
                    'attempts' => $attempts
                ]);
                
                return response()->json([
                    'error' => 'Te veel wijzigingen. Probeer het over een minuut opnieuw.'
                ], 429);
            }
            
            cache()->put($key, $attempts + 1, 60); // 60 seconden
        }

        // Valideer dat gebruiker geautoriseerd is voor allergie wijzigingen
        if (str_contains($request->path(), 'edit') || str_contains($request->path(), 'update')) {
            if (!auth()->check()) {
                Log::warning('Unauthorized allergie modification attempt', [
                    'ip' => $request->ip(),
                    'path' => $request->path(),
                    'user_agent' => $request->userAgent()
                ]);
                
                return redirect()->route('login')->with('error', 'U moet ingelogd zijn om allergieën te wijzigen.');
            }
        }

        return $next($request);
    }
}
