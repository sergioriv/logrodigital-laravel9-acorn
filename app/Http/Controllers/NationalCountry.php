<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;

class NationalCountry extends Controller
{
    public static function country()
    {
        return Country::where('national', 1)->first();
    }
}
