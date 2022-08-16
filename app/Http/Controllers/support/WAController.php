<?php

namespace App\Http\Controllers\support;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WAController extends Controller
{
    private $message;
    private $phone;

    function __construct($message, $phone)
    {
        $this->message = $message;
        $this->phone = $phone;
    }

    public function send () {
        $data = [
            'message' => $this->message,
            'phone' => '57' . $this->phone
        ];
        $response = Http::post(env('API_WA'), $data);
        dd($response);
    }
}
