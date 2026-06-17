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

        // Check if this is an admin route
        if ($request->is('admin/*') || $request->is('admin')) {
            return route('admin.login');
        }

        // For other routes, we don't have a login route, so return null
        return null;
    }
}
