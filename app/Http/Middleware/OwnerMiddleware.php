<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class OwnerMiddleware
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
        $id = Auth::guard('api')->id();
        $role = Auth::guard('api')->user()->role;


        if ($request->is('api/organization/*')) {

            $user_id = $request->route('organization')->creator->id;

            if ($user_id === $id || $role == 'admin') {
                return $next($request);
            }
            abort(404, '404 not found.');
        } elseif ($request->is('api/user/*')) {

            $user_id = $request->route('user')->id;

            if ($user_id === $id || $role == 'admin') {
                return $next($request);
            }
            abort(404, '404 not found.');
        } elseif ($request->is('api/vacancy/*')) {

            $user_id = $request->route('vacancy')->company->creator->id;

            if ($user_id === $id || $role == 'admin') {
                return $next($request);
            }
            abort(404, '404 not found.');
        }
        abort(404, '404 not found.');
    }
}
