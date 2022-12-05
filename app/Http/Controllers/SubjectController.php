<?php

namespace App\Http\Controllers;

use App\Http\Controllers\support\Notify;
use App\Http\Middleware\YearCurrentMiddleware;
use App\Models\ResourceArea;
use App\Models\ResourceSubject;
use App\Models\SchoolYear;
use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:subjects.index');
        $this->middleware('can:subjects.edit')->only('store');

        $this->middleware(YearCurrentMiddleware::class)->only('store');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Y = SchoolYearController::current_year();

        if (NULL === $Y->available) {
            $resourceAreas = ResourceArea::whereHas('subjects', function ($subjects) use ($Y) {
                $subjects->where('school_year_id', $Y->id);
            })->get();
        } else {
            $resourceAreas = ResourceArea::all();
        }

        $resourceSubjects = ResourceSubject::whereNot(function ($query) use ($Y) {
            $query->whereHas('subjects', function ($subject) use ($Y) {
                $subject->where('school_year_id', $Y->id);
            });
        })->get();

        $subjects = Subject::whereHas('resourceSubject', fn($rs) => $rs->whereNull('specialty'))
            ->where('school_year_id', $Y->id)->get();
        return view('logro.subject.index')->with([
            'Y' => $Y,
            'resourceAreas' => $resourceAreas,
            'resourceSubjects' => $resourceSubjects,
            'subjects' => $subjects
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $Y = SchoolYearController::current_year();

        foreach ($request->subjects as $are_subject) {
            [$area, $subject] = explode('~', $are_subject);
            if ('null' !== $area) {
                Subject::create([
                    'school_year_id' => $Y->id,
                    'resource_area_id' => $area,
                    'resource_subject_id' => $subject
                ]);
            }
        }

        Notify::success(__('Areas & Subjects updated!'));
        return redirect()->route('subject.index');
    }

}
