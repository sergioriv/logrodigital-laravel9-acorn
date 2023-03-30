<?php

namespace App\Http\Controllers;

use App\Http\Controllers\support\Notify;
use App\Models\TypePermitsTeacher;
use Illuminate\Http\Request;

class OtherOptionsController extends Controller
{
    public function index()
    {
        return view(
            'logro.other-options.index',
            [
                'typePermits' => TypePermitsTeacher::all()
            ]
        );
    }


    public function create(Request $request)
    {
        if (is_null($request->id)) {

            return view(
                'logro.other-options.type-permits.create',
                [
                    'title' => __('Create'),
                    'route' => route('type-permission.store'),
                    'typePermission' => null,
                    'method' => 'POST'
                ]
            );
        } else {

            $typePermission = TypePermitsTeacher::find($request->id);

            return view(
                'logro.other-options.type-permits.create',
                [
                    'title' => $typePermission->name,
                    'route' => route('type-permission.update', $typePermission),
                    'typePermission' => $typePermission,
                    'method' => 'PUT'
                ]
            );

        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:191']
        ]);

        TypePermitsTeacher::create([
            'name' => $request->name
        ]);

        Notify::success(__('Permission type created!'));
        return back();
    }

    public function update(Request $request, TypePermitsTeacher $typePermission)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:191']
        ]);

        $typePermission->update([
            'name' => $request->name
        ]);

        Notify::success(__('Permission type updated!'));
        return back();
    }
}
