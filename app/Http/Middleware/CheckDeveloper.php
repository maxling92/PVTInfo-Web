<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckDeveloper
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Replace with your user ID or any condition that identifies you
        $developerUserId = 1; // Example: user ID of the developer

        if (Auth::check() && Auth::id() == $developerUserId) {
            return $next($request);
        }

        return redirect('/')->with('error', 'Unauthorized access');
    }
}

