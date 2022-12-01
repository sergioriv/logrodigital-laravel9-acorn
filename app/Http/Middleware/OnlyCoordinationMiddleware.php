<?php

namespace App\Http\Middleware;

use App\Http\Controllers\support\Notify;
use App\Models\Data\RoleUser;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OnlyCoordinationMiddleware
{

    /**
     * If the current school year is not available, redirect back with a notification
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if( !Auth::user()->hasRole(RoleUser::COORDINATION_ROL) )
        {
            Notify::fail(__('Not allowed'));
            return redirect()->back();
        }

        return $next($request);
    }
}
