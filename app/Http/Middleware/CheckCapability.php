<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckCapability
{
    public function handle(Request $request, Closure $next, string $capability)
    {
        $user = $request->user();

        // kalau belum login
        if (!$user) abort(403);

        // developer override (kalau kamu masih mau konsep ini)
        if ($user->isDeveloper()) {
            return $next($request);
        }

        // cek kolom boolean di role
        if (!(bool) ($user->role?->{$capability})) {
            abort(403);
        }

        return $next($request);
    }
}
