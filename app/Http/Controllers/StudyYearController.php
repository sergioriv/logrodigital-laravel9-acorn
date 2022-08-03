<?php

namespace App\Http\Controllers;


use App\Models\ResourceArea;
use App\Models\SchoolYear;
use App\Models\StudyYear;
use App\Models\StudyYearSubject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use phpDocumentor\Reflection\Types\Resource_;

class StudyYearController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:studyYear.index');
        $this->middleware('can:studyYear.create')->only('create','store','edit','update');
        $this->middleware('can:studyYear.subjects')->only('subjects','subjects_store','subjects_edit');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Y = SchoolYearController::current_year();

        $studyYears = StudyYear::withCount(['studyYearSubject' =>
                fn ($subjects) => $subjects->where('school_year_id', $Y->id)
            ])
            ->withCount(['groups' =>
                fn ($groups) => $groups->where('school_year_id', $Y->id)
            ])
            ->withSum(['groups' =>
                fn ($groups) => $groups->where('school_year_id', $Y->id)
            ], 'student_quantity')
            ->get();

        return view('logro.studyyear.index')->with([
            'Y' => $Y->name,
            'studyYears' => $studyYears
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    /* public function create()
    {
        return view('support.studyyear.create');
    } */

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    /* public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', Rule::unique('study_years')]
        ]);

        StudyYear::create([
            'name' => $request->name,
            'available' => TRUE
        ]);

        return redirect()->route('studyYear.index')->with(
            ['notify' => 'success', 'title' => __('Study year created!')],
        );
    } */

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\StudyYear  $studyYear
     * @return \Illuminate\Http\Response
     */
    /* public function show(StudyYear $studyYear)
    {
        //
    } */

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\StudyYear  $studyYear
     * @return \Illuminate\Http\Response
     */
    /* public function edit(StudyYear $studyYear)
    {
        return view('support.studyyear.edit')->with('studyYear', $studyYear);
    } */

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\StudyYear  $studyYear
     * @return \Illuminate\Http\Response
     */
    /* public function update(Request $request, StudyYear $studyYear)
    {
        $request->validate([
            'name' => ['required', 'string', Rule::unique('study_years')->ignore($studyYear->id)]
        ]);

        $studyYear->update([
            'name' => $request->name
        ]);

        return redirect()->route('studyYear.index')->with(
            ['notify' => 'success', 'title' => __('Study year updated!')],
        );
    } */

    public function subjects(StudyYear $studyYear)
    {
        $Y = SchoolYearController::current_year();

        $subject_count = StudyYearSubject::where('school_year_id', $Y->id)->where('study_year_id', $studyYear->id)->count();

        if ($subject_count == 0 && NULL !== $Y->available)
        {
            /*
             * Create Study Year Subjects
             */
            $areas = ResourceArea::with(['subjects' =>
                    fn($s) => $s->where('school_year_id', $Y->id)
                ])->get();

            return view('logro.studyyear.subjects')->with([
                'studyYear' => $studyYear,
                'areas' => $areas,
            ]);

        } else
        {
            /*
             * Show Study Year Subjects
             */
            $fn_study_year = fn($sy) =>
                $sy->where('school_year_id', $Y->id)
                ->where('study_year_id', $studyYear->id);

            $fn_subjects = fn($s) =>
                $s->where('school_year_id', $Y->id)
                ->whereHas('studyYearSubject', $fn_study_year)
                ->with(['studyYearSubject' => $fn_study_year]);

            $areas = ResourceArea::with(['subjects' => $fn_subjects])
                    ->whereHas('subjects', $fn_subjects)
                    ->get();

            return view('logro.studyyear.subjects_show')->with([
                'Y' => $Y,
                'studyYear' => $studyYear,
                'areas' => $areas,
            ]);
        }

    }

    public function subjects_edit(StudyYear $studyYear)
    {
        $Y = SchoolYearController::current_year();

        if (NULL !== $Y->available)
        {

            $fn_study_year = fn($sy) =>
                    $sy->where('school_year_id', $Y->id)
                    ->where('study_year_id', $studyYear->id);

            $fn_subjects = fn($s) =>
                    $s->where('school_year_id', $Y->id)
                    // ->whereHas('studyYearSubject', $fn_study_year)
                    ->with(['studyYearSubject' => $fn_study_year]);

            $areas = ResourceArea::with(['subjects' => $fn_subjects])
                        // ->whereHas('subjects', $fn_subjects)
                        ->get();

            return view('logro.studyyear.subjects_edit')->with([
                'studyYear' => $studyYear,
                'areas' => $areas,
            ]);

        } else
        {
            return redirect()->back()->with(
                ['notify' => 'fail', 'title' => __('Not allowed for ') . $Y->name],
            );
        }
    }

    public function subjects_store(StudyYear $studyYear, Request $request)
    {
        $request->validate([
            'subjects' => ['required', 'array']
        ]);

        $Y = SchoolYearController::current_year();

        if (NULL !== $Y->available)
        {

            DB::beginTransaction();

            $areas = [];
            $total_course_load = 0;

            foreach ($request->subjects as $area_subject) {

                // [$area, $subject, $exist] = explode('~', @$area_subject);
                $explode = explode('~',$area_subject);
                $area = $explode[0];
                $subject = $explode[1];
                $exist = @$explode[2];

                array_push($areas, $area);

                $hours_week = $subject.'~hours_week';
                $course_load = $subject.'~course_load';

                if (empty($request->$hours_week) || empty($request->$course_load))
                {
                    DB::rollBack();
                    return redirect()->back()->withErrors( __("empty fields") );
                }

                if ($request->$course_load > 100)
                {
                    DB::rollBack();
                    return redirect()->back()->withErrors( __("academic load must not exceed 100%") );
                }

                $total_course_load += $request->$course_load;

                if ('null' !== $exist && isset($exist))
                {
                    /*
                     * StudyYearSubject modified
                     */
                    $sy_subject = StudyYearSubject::where('id',$exist)
                        ->where('school_year_id', $Y->id)
                        ->where('study_year_id', $studyYear->id)
                        ->where('subject_id', $subject)
                        ->first();

                    if ($sy_subject)
                    {
                        $sy_subject->update([
                            'hours_week' => $request->$hours_week,
                            'course_load' => $request->$course_load
                        ]);
                    } else
                    {
                        DB::rollBack();
                        return redirect()->back()->withErrors( __("Unexpected Error") );
                    }

                } else
                {
                    /*
                     * StudyYearSubject Created
                     */
                    StudyYearSubject::create([
                        'school_year_id' => $Y->id,
                        'study_year_id' => $studyYear->id,
                        'subject_id' => $subject,
                        'hours_week' => $request->$hours_week,
                        'course_load' => $request->$course_load
                    ]);
                }


            }

            $areas_total = count(array_unique($areas)) * 100;

            if ($total_course_load === $areas_total)
            {
                DB::commit();
                return redirect()->route('studyYear.subject.show', $studyYear)->with(
                    ['notify' => 'success', 'title' => $studyYear->name .' '. __('updated!')],
                );
            } else
            {
                DB::rollBack();
                return redirect()->back()->withErrors( __("check the course load") );
            }

        } else
        {
            return redirect()->back()->with(
                ['notify' => 'fail', 'title' => __('Not allowed for ') . $Y->name],
            );
        }

    }

    // public function subjects_update(StudyYear $studyYear, Request $request)
    // {
    //     $request->validate([
    //         'subjects' => ['required', 'array']
    //     ]);

    //     $Y = SchoolYearController::current_year();

    //     if (NULL !== $Y->available)
    //     {
    //         // DB::beginTransaction();

    //         $areas = [];
    //         $total_course_load = 0;

    //         foreach ($request->subjects as $area_subject) {

    //             [$exist, $area, $subject] = explode('~',$area_subject);
    //             array_push($areas, $area);

    //             $hours_week = $subject.'~hours_week';
    //             $course_load = $subject.'~course_load';

    //             if (empty($request->$hours_week) || empty($request->$course_load))
    //             {
    //                 DB::rollBack();
    //                 return redirect()->back()->withErrors( __("empty fields") );
    //             }

    //             if ($request->$course_load > 100)
    //             {
    //                 DB::rollBack();
    //                 return redirect()->back()->withErrors( __("academic load must not exceed 100%") );
    //             }

    //             $total_course_load += $request->$course_load;

    //             /* StudyYearSubject::create([
    //                 'school_year_id' => $Y->id,
    //                 'study_year_id' => $studyYear->id,
    //                 'subject_id' => $subject,
    //                 'hours_week' => $request->$hours_week,
    //                 'course_load' => $request->$course_load
    //             ]); */
    //         }

    //         $areas_total = count(array_unique($areas)) * 100;

    //         if ($total_course_load === $areas_total)
    //         {
    //             // DB::commit();
    //             return redirect()->route('studyYear.subject.show', $studyYear)->with(
    //                 ['notify' => 'success', 'title' => __('Updated!')],
    //             );
    //         } else
    //         {
    //             // DB::rollBack();
    //             return redirect()->back()->withErrors( __("check the course load") );
    //         }

    //     } else
    //     {
    //         return redirect()->back()->with(
    //             ['notify' => 'fail', 'title' => __('Not allowed for ') . $Y->name],
    //         );
    //     }

    //     dd($request);
    // }


}
