<?php

namespace App\Http\Controllers;

use App\Http\Controllers\support\Notify;
use App\Http\Middleware\YearCurrentMiddleware;
use App\Models\ResourceArea;
use App\Models\StudyYear;
use App\Models\StudyYearSubject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudyYearController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:studyYear.index');
        $this->middleware('can:studyYear.create')->only('create', 'store', 'edit', 'update');
        $this->middleware('can:studyYear.subjects')->only('subjects', 'subjects_store', 'subjects_edit');

        $this->middleware(YearCurrentMiddleware::class)->except('subjects_edit', 'subjects_store');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Y = SchoolYearController::current_year();

        $studyYears = StudyYear::withCount([
            'studyYearSubject' =>
            fn ($subjects) => $subjects->where('school_year_id', $Y->id)
        ])
            ->withCount([
                'groups' =>
                fn ($groups) => $groups->where('school_year_id', $Y->id)
            ])
            ->withSum([
                'groups' =>
                fn ($groups) => $groups->where('school_year_id', $Y->id)
            ], 'student_quantity')
            ->get();

        return view('logro.studyyear.index')->with([
            'Y' => $Y->name,
            'studyYears' => $studyYears
        ]);
    }

    public function subjects(StudyYear $studyYear)
    {
        $Y = SchoolYearController::current_year();

        $subject_count = StudyYearSubject::where('school_year_id', $Y->id)->where('study_year_id', $studyYear->id)->count();

        if ($subject_count == 0 && NULL !== $Y->available) {
            /*
             * Create Study Year Subjects
             */
            $areas = ResourceArea::with([
                'subjects' =>
                fn ($s) => $s->where('school_year_id', $Y->id)
            ])->get();

            return view('logro.studyyear.subjects')->with([
                'studyYear' => $studyYear,
                'areas' => $areas,
            ]);
        } else {
            /*
             * Show Study Year Subjects
             */
            $fn_study_year = fn ($sy) =>
            $sy->where('school_year_id', $Y->id)
                ->where('study_year_id', $studyYear->id);

            $fn_subjects = fn ($s) =>
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

        $fn_study_year = fn ($sy) =>
        $sy->where('school_year_id', $Y->id)
            ->where('study_year_id', $studyYear->id);

        $fn_subjects = fn ($s) =>
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
    }

    public function subjects_store(StudyYear $studyYear, Request $request)
    {
        $request->validate([
            'subjects' => ['required', 'array']
        ]);

        $Y = SchoolYearController::current_year();


        DB::beginTransaction();

        $areas = [];
        $total_course_load = 0;

        foreach ($request->subjects as $area_subject) {

            // [$area, $subject, $exist] = explode('~', @$area_subject);
            $explode = explode('~', $area_subject);
            $area = $explode[0];
            $subject = $explode[1];
            $exist = @$explode[2];

            array_push($areas, $area);

            $hours_week = $subject . '~hours_week';
            $course_load = $subject . '~course_load';

            if (empty($request->$hours_week) || empty($request->$course_load)) {
                DB::rollBack();
                return redirect()->back()->withErrors(__("empty fields"));
            }

            if ($request->$course_load > 100) {
                DB::rollBack();
                return redirect()->back()->withErrors(__("academic load must not exceed 100%"));
            }

            $total_course_load += $request->$course_load;

            if ('null' !== $exist && isset($exist)) {
                /*
                     * StudyYearSubject modified
                     */
                $sy_subject = StudyYearSubject::where('id', $exist)
                    ->where('school_year_id', $Y->id)
                    ->where('study_year_id', $studyYear->id)
                    ->where('subject_id', $subject)
                    ->first();

                if ($sy_subject) {
                    $sy_subject->update([
                        'hours_week' => $request->$hours_week,
                        'course_load' => $request->$course_load
                    ]);
                } else {
                    DB::rollBack();
                    return redirect()->back()->withErrors(__("Unexpected Error"));
                }
            } else {
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

        if ($total_course_load === $areas_total) {
            DB::commit();
            Notify::success($studyYear->name . ' ' . __('updated!'));
            return redirect()->route('studyYear.subject.show', $studyYear);
        } else {
            DB::rollBack();
            return redirect()->back()->withErrors(__("check the course load"));
        }
    }

}
