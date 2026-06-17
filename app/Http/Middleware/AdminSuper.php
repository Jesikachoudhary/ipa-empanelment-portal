<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminSuper
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::guard('admin')->user();
        if (!$user) {
            abort(403);
        }

        // Super-admin allowed for everything
        if ($user->is_super) {
            return $next($request);
        }

        // Allow the creator (owner) to view/edit their own applicant 'show'/'edit'/'update' pages
        $routeName = optional($request->route())->getName();
        $ownerAllowedRoutes = [
            'admin.applicants.show',
            'admin.applicants.edit',
            'admin.applicants.update',
        ];
        if (in_array($routeName, $ownerAllowedRoutes, true)) {
            $applicant = $request->route('applicant');
            if ($applicant && $applicant->admin_id === $user->id) {
                return $next($request);
            }
        }

        // Otherwise deny
        abort(403);
    }
}
