<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Localization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check header request and determine localizaton
        $local = ($request->hasHeader('X-Localization')) ? $request->header('X-Localization') : config('app.locale');

        // Set laravel localization
        app()->setLocale($local);

        // Continue request
        return $next($request);
    }
}
