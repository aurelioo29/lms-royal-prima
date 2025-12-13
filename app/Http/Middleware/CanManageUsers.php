<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CanManageUsers
{
    public function handle($request, Closure $next)
    {
        if (!auth()->user()?->canManageUsers()) {
            abort(403);
        }

        return $next($request);
    }
}
