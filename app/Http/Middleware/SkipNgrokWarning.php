<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SkipNgrokWarning
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Tell ngrok to skip the browser warning
        $request->headers->set('ngrok-skip-browser-warning', 'true');

        return $next($request);
    }
}
