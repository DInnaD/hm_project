<?php

namespace App\Http\Middleware;

use Closure;

class IsEmployerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $role = Auth::guard('api')->user()->role;

        if ($role != 'employer') {
            abort(404, "404 not found.");
        }

        return $next($request);
    }
}
