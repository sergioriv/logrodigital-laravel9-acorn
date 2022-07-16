<?php

namespace App\Http\Controllers;

use App\Models\ResourceArea;
use App\Models\ResourceSubject;
use App\Models\SchoolYear;
use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function __construct()
    {
        // $this->middleware('can:subject');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Y = SchoolYearController::current_year();

        if (NULL === $Y->available)
        {
            $resourceAreas = ResourceArea::whereHas('subjects', function ($subjects) use ($Y) {
                $subjects->where('school_year_id', $Y->id);
            })->get();
        } else
        {
            $resourceAreas = ResourceArea::all();
        }

        $resourceSubjects = ResourceSubject::whereNot(function ($query) use ($Y) {
            $query->whereHas('subjects', function ($subject) use ($Y) {
                $subject->where('school_year_id', $Y->id);
            });
        })->get();

        $subjects = Subject::with('resourceSubject')->where('school_year_id', $Y->id)->get();
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

        if (NULL !== $Y->available)
        {

            foreach ($request->subjects as $are_subject) {
                [$area, $subject] = explode('~',$are_subject);
                if ('null' !== $area) {
                    Subject::create([
                        'school_year_id' => $Y->id,
                        'resource_area_id' => $area,
                        'resource_subject_id' => $subject
                    ]);
                }
            }

            return redirect()->route('subject.index')->with(
                ['notify' => 'success', 'title' => __('Areas & Subjects updated!')],
            );

        } else
        {
            return redirect()->back()->with(
                ['notify' => 'fail', 'title' => __('Not allowed for ') . $Y->name],
            );
        }
    }

    /* public static function year_studyYear_subjects($Y_id, $sy_id)
    {
        $fn_sy = fn($sy) =>
                $sy->where('school_year_id', $Y_id)
                ->where('study_year_id', $sy_id);

        $fn_sb = fn($s) =>
                $s->where('school_year_id', $Y_id)
                ->whereHas('studyYearSubject', $fn_sy)
                ->with(['studyYearSubject' => $fn_sy]);

        $a_sb = ResourceArea::with(['subjects' => $fn_sb])
                    ->whereHas('subjects', $fn_sb)
                    ->get();

        return $a_sb;
    } */
}
