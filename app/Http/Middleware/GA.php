<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class GA
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

    public function __construct()
    {
        if (!Auth::user()) {
            return route('login');
        }
    }

    public function handle($request, Closure $next)
    {
        if (Auth::user()) {
            if (Auth::user()->role == 1 || Auth::user()->role == 4) {
                return $next($request);
            } else {
                abort(401, 'Unauthorized user.');
            }
        } else {
            return redirect('login');
        }
    }
}
