<?php

namespace App\Http\Middleware;

use App\Http\Controllers\SchoolController;
use App\Http\Controllers\support\Notify;
use App\Models\Student;
use Closure;
use Illuminate\Http\Request;

class CheckStudentCountMiddleware
{
    /**
     * If the number of students is greater than or equal to the maximum number of students, redirect
     * back with a fail message. If the number of students is greater than or equal to the maximum
     * number of students minus 100, redirect back with an info message. Otherwise, continue
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

        $S = SchoolController::myschool()->getData();

        if ( Student::available()->count() >= $S->number_students)
        {
            Notify::fail(__('Has reached the limit of the contracted plan.'));
            return redirect()->back();
        }

        return $next($request);
    }
}
