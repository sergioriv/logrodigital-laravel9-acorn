<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function index()
    {
        return view('logro.calendar.index');
    }

    public function data()
    {

        $data = [
            [
                "id" => 'calendar-1',
                "title" => "Crash Course",
                "start" => "2023-02-05",
                "end" => "2023-02-07",
                "color" => "",
                "category" => "Event"
            ],
            [
                "id" => 'calendar-2',
                "title" => "Sale Meetings",
                "start" => "2023-02-09",
                "end" => "2023-02-12",
                "color" => "",
                "category" => "Event"
            ],
            [
                "id" => 'calendar-3',
                "title" => "Birthday Party",
                "start" => "2023-02-22",
                "color" => "#1859bb",
                "category" => "Event"
            ],
            [
                "id" => 'calendar-4',
                "title" => "Link",
                "start" => "2023-02-28",
                "url" => "https://themeforest.net/user/coloredstrategies/portfolio/",
                "color" => "",
                "category" => "Event"
            ],
            [
                "id" => 'calendar-5',
                "title" => "Meeting",
                "start" => "2023-02-20T10:30:00",
                "end" => "2023-02-20T12:30:00",
                "color" => "",
                "category" => "Advice"
            ],
            [
                "id" => 'calendar-6',
                "title" => "Lunch",
                "start" => "2023-02-20T14:30:00",
                "end" => "2023-02-20T15:30:00",
                "color" => "",
                "category" => "Advice"
            ],
            [
                "id" => 'calendar-7',
                "title" => "Dinner",
                "start" => "2023-02-20T19:30:00",
                "end" => "2023-02-20T21:30:00",
                "color" => "",
                "category" => "Event"
            ],
            [
                "id" => 'calendar-8',
                "title" => "test 01",
                "start" => "2023-02-20T19:30:00",
                "end" => "2023-02-20T21:30:00",
                "color" => "",
                "category" => "Event"
            ],
            [
                "id" => 'calendar-9',
                "title" => "ACOSTA MEDINA",
                "start" => "2023-02-21T19:30:00",
                "end" => "2023-02-21T21:30:00",
                "url" => "http://logro.digital.test/student/1135",
                "color" => "",
                "category" => "Advice"
            ]
        ];


        return response()->json($data);
    }
}
