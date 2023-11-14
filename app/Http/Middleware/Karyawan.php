<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class Karyawan
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
            if ((Auth::user()->role == 1 || Auth::user()->role == 2 || Auth::user()->role == 3 || Auth::user()->role == 4 || Auth::user()->role == 5 || Auth::user()->role == 6) && Auth::user()->deleted == 0) {
                return $next($request);
            } else {
                Auth::logout();
                abort(401, 'Unauthorized user.');
            }
        } else {
            return redirect('login');
        }
    }
}
