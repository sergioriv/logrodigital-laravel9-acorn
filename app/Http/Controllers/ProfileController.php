<?php

namespace App\Http\Controllers;


use App\Http\Controllers\support\UserController;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        switch (UserController::role_auth()) {
             /* case 'Support':
                $support = User::findOrFail(Auth::user()->id);
                return view('profile.support-edit')->with('support', $support);
                break; */

            /* case 'Student':
                $student = User::findOrFail(Auth::user()->id);
                return view('profile.estudent-edit')->with('student', $student);
                break; */
/*
            case 'Branch':
                $branch = Branch::with('user')->findOrFail(Auth::user()->id);
                $deps = json_decode(file_get_contents('json/colombia.min.json'), true);

                return view('profile.branch-edit')->with(['branch' => $branch, 'deps' => $deps]);
                break; */

            default:
                return $this->not_found();
                break;
        }
    }

    public function edit()
    {
        switch (UserController::role_auth()) {

            case 'Support':
                $support = User::findOrFail(Auth::user()->id);
                return view('profile.support-edit')->with('support', $support);
                break;

            case 'Student':
                $student = new StudentController();
                return $student->show( Student::find(Auth::user()->id) );
                break;

            /* case 'Restaurant':
                $restaurant = Restaurant::with('user')->findOrFail(Auth::user()->id);
                return view('profile.restaurant-edit')->with('restaurant', $restaurant);
                break; */

            default:
                return $this->not_found();
                break;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        switch (UserController::role_auth()) {
            case 'Support':
                $support = User::findOrFail(Auth::user()->id);
                UserController::profile_update($request, $support);
                break;

            case 'Student':
                $student = Student::findOrFail(Auth::user()->id);
                $update = new StudentController();
                $update->update($request, $student);
                break;

            /* case 'Restaurant':
                $restaurant = Restaurant::findOrFail(Auth::user()->id);
                RestaurantController::profile_update($request, $restaurant);
                break; */

            default:
                return $this->not_found();
                break;
        }

        return redirect()->route('user.profile.edit')->with(
            ['notify' => 'success', 'title' => __('Updated!')],
        );
    }

    private function not_found()
    {
        return redirect()->route('dashboard')->with(
            ['notify' => 'fail', 'title' => __('Unauthorized!')],
        );
    }
}
