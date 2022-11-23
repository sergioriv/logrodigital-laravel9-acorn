<?php

namespace App\Http\Middleware;

use App\Http\Controllers\support\Notify;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OnlyTeachersMiddleware
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
        if( !Auth::user()->hasRole('TEACHER') )
        {
            Notify::fail(__('Not allowed'));
            return redirect()->back();
        }

        return $next($request);
    }
}
