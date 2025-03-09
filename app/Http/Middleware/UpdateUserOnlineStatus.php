<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class UpdateUserOnlineStatus
{
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $user->is_online = true;
            $user->save();
        }

        return $next($request);
    }

    public function terminate($request, $response)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $user->is_online = false;
            $user->save();
        }
    }
}
