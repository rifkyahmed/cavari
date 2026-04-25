<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ContentSecurityPolicy
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        $csp = "default-src 'self'; ".
               "media-src 'self' https://res.cloudinary.com; ".
               "img-src 'self' https://res.cloudinary.com; ".
               "font-src 'self' https://res.cloudinary.com;";

        $response->headers->set('Content-Security-Policy', $csp);

        return $response;
    }
}
