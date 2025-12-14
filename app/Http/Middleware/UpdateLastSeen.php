<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class UpdateLastSeen
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if ($user) {
            $key = "last_seen_write:user:{$user->id}";

            // write max once per 30s
            if (!Cache::has($key)) {
                Cache::put($key, true, now()->addSeconds(30));

                $user->forceFill([
                    'last_seen_at' => now(),
                ])->saveQuietly();
            }
        }

        return $next($request);
    }
}
