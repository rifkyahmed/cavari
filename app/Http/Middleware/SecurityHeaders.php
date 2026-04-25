<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Security Headers
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');

        // HSTS (Only apply on HTTPS)
        if ($request->secure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        }

        // Content Security Policy – now includes media-src for Cloudinary
        $csp = "default-src 'self'; "
            . "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://js.stripe.com https://cdn.jsdelivr.net https://ajax.googleapis.com https://unpkg.com http://127.0.0.1:5173; "
            . "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com http://127.0.0.1:5173; "
            . "font-src 'self' https://fonts.gstatic.com; "
            . "img-src 'self' data: https: http://127.0.0.1:5173; "
            . "media-src 'self' https://res.cloudinary.com; "   // <-- added
            . "frame-src 'self' https://js.stripe.com; "
            . "connect-src 'self' https://api.stripe.com ws://127.0.0.1:5173 http://127.0.0.1:5173;";

        $response->headers->set('Content-Security-Policy', $csp);

        return $response;
    }
}
