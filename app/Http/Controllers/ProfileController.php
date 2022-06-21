<?php

namespace App\Http\Controllers;

use App\Http\Controllers\branch\WaiterController;
use App\Http\Controllers\restaurant\BranchController;
use App\Http\Controllers\support\RestaurantController;
use App\Http\Controllers\support\UserController;
use App\Models\Branch;
use App\Models\Restaurant;
use App\Models\User;
use App\Models\Waiter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
             case 'Support':
                $support = User::findOrFail(Auth::user()->id);
                return view('profile.support-edit')->with('support', $support);
                break;
/*
            case 'Branch':
                $branch = Branch::with('user')->findOrFail(Auth::user()->id);
                $deps = json_decode(file_get_contents('json/colombia.min.json'), true);

                return view('profile.branch-edit')->with(['branch' => $branch, 'deps' => $deps]);
                break;

            case 'Waiter':
                $waiter = Waiter::with('user')->findOrFail(Auth::user()->id);
                return view('profile.waiter-edit')->with('waiter', $waiter);
                break; */

            default:
                return $this->not_found();
                break;
        }
    }

    public function edit()
    {
        switch (UserController::role_auth()) {
            /* case 'Restaurant':
                $restaurant = Restaurant::with('user')->findOrFail(Auth::user()->id);
                return view('profile.restaurant-edit')->with('restaurant', $restaurant);
                break;

            case 'Branch':
                $branch = Branch::with('user')->findOrFail(Auth::user()->id);
                return view('profile.branch-edit')->with('branch', $branch);
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

            /* case 'Restaurant':
                $restaurant = Restaurant::findOrFail(Auth::user()->id);
                RestaurantController::profile_update($request, $restaurant);
                break;

            case 'Branch':
                $branch = Branch::findOrFail(Auth::user()->id);
                BranchController::profile_update($request, $branch);
                break;

            case 'Waiter':
                $waiter = Waiter::findOrFail(Auth::user()->id);
                WaiterController::profile_update($request, $waiter);
                break; */

            default:
                return $this->not_found();
                break;
        }

        return redirect()->route('user.profile')->with(
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
