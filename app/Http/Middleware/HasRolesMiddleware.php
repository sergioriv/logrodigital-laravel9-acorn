<?php

namespace App\Http\Middleware;

use App\Http\Controllers\support\Notify;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HasRolesMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, string ...$roles)
    {

        if ( empty( array_intersect($roles, Auth::user()->getRoleNames()->toArray()) ) ) {
            Notify::fail(__('Not allowed'));
            return back();
        }

        return $next($request);
    }
}
