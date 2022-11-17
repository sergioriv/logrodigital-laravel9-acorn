<?php

namespace App\Http\Controllers\support;

use App\Http\Controllers\Controller;

class Notify extends Controller
{
    public static function success($title) {
        return session()->flash('notify', 'success|'. $title);
    }
    public static function fail($title) {
        return session()->flash('notify', 'fail|'. $title);
    }
    public static function info($title) {
        return session()->flash('notify', 'info|'. $title);
    }
    public static function welcome($title) {
        return session()->flash('notify', 'welcome|'. $title);
    }
}
