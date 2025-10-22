<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PreventRequestsDuringMaintenance
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (file_exists(storage_path('framework/maintenance.php'))) {
            abort(503, 'Service Unavailable');
        }

        return $next($request);
    }
}
