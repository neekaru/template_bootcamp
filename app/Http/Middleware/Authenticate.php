<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if ($request->expectsJson()) {
            return null;
        }
        
        // Check if the requested guard is 'pembeli'
        if ($request->routeIs('pembeli.*') || in_array('pembeli', $request->route()->middleware())) {
            return route('login');
        }

        return route('login');
    }
}