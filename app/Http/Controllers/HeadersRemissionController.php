<?php

namespace App\Http\Controllers;

use App\Http\Controllers\support\Notify;
use App\Models\HeadersRemission;
use Illuminate\Http\Request;

class HeadersRemissionController extends Controller
{
    public function index()
    {
        return view(
            'logro.headers-remissions.index',
            [
                'headers' => HeadersRemission::with('orientator')->get()
            ]
        );
    }

    public function json()
    {
        return response()->json(['data' => HeadersRemission::all()]);
    }

    public function create()
    {
        return view(
            'logro.headers-remissions.create'
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:191'],
            'content' => ['required', 'string', 'max:3000']
        ]);

        try {

            HeadersRemission::create([
                'title' => trim($request->title),
                'content' => trim(nl2br($request->content)),
                'orientation_id' => auth()->id()
            ]);

            Notify::success(__('Header created!'));

        } catch (\Throwable $th) {

            Notify::fail(__('An error has occurred'));
        }

        return redirect()->route('headers-remissions.index');
    }

    public function edit(HeadersRemission $headersRemission)
    {
        return $headersRemission;
        return view(
            'logro.headers-remissions.edit',
            [
                'header' => $headersRemission
            ]
        );
    }

    public function update(Request $request, HeadersRemission $headersRemission)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:191'],
            'content' => ['required', 'string', 'max:3000']
        ]);

        try {

            $headersRemission->update([
                'title' => trim($request->title),
                'content' => trim($request->content),
            ]);

            Notify::success(__('Header updated!'));

        } catch (\Throwable $th) {

            Notify::fail(__('An error has occurred'));
        }

        return redirect()->route('headers-remissions.index');
    }

}
