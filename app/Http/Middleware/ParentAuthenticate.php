<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware; 
use Illuminate\Http\Request;

class ParentAuthenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string {
        if (!$request->expectsJson()) {
            if ($request->is('parent') || $request->is('parent/*')) {
                return route('parent.login');
            }
        }
    }
}
