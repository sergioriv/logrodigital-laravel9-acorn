<?php

namespace App\Http\Middleware;

use App\Http\Controllers\SchoolYearController;
use Closure;
use Illuminate\Http\Request;

class YearCurrentMiddleware
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
        $Y = SchoolYearController::current_year();

        if( NULL === $Y->available )
            return redirect()->back()->with(
                ['notify' => 'fail', 'title' => __('Not allowed for ') . $Y->name],
            );

        return $next($request);
    }
}
