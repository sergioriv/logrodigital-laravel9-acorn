<?php

namespace App\Http\Controllers;

use App\Exports\GroupStudentListGuide;
use App\Http\Controllers\support\Notify;
use App\Http\Middleware\YearCurrentMiddleware;
use App\Models\ResourceArea;
use App\Models\ResourceStudyYear;
use App\Models\StudyYear;
use App\Models\AcademicWorkload;
use App\Models\TeacherSubjectGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;

class StudyYearController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:studyYear.index');
        $this->middleware('can:studyYear.create')->only('create', 'store', 'edit', 'update');
        $this->middleware('can:studyYear.subjects')->only('subjects', 'subjects_store', 'subjects_edit');

        $this->middleware(YearCurrentMiddleware::class)->except('index', 'subjects_edit', 'subjects_store');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Y = SchoolYearController::current_year();

        $studyYears = StudyYear::
            withCount(['groups' => function ($groups) {
                $groups->whereHas('teacherSubjectGroups');
            }])
            ->get();

        return view('logro.studyyear.index')->with([
            'Y' => $Y,
            'studyYears' => $studyYears
        ]);
    }

    public function create()
    {
        return view('logro.studyyear.create', ['resourceStudyYears' => ResourceStudyYear::all()]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', Rule::unique('study_years', 'name')],
            'study_year' => ['required', Rule::exists('resource_study_years', 'uuid')]
        ]);

        $resource = ResourceStudyYear::where('uuid', $request->study_year)->first();

        $studyYear = StudyYear::create([
            'name' => $request->name,
            'resource_study_year_id' => $resource->id,
            'use_grades' => TRUE,
            'use_components' => FALSE,
            'use_descriptors' => FALSE,
        ]);

        return redirect()->route('studyYear.subject.show', $studyYear);
    }

    public function edit(StudyYear $studyYear)
    {
        return view('logro.studyyear.edit', [
            'studyYear' => $studyYear,
            'resourceStudyYears' => ResourceStudyYear::all()
        ]);
    }

    public function update(StudyYear $studyYear, Request $request)
    {
        $request->validate([
            'name' => ['required', Rule::unique('study_years', 'name')->ignore($studyYear->id)],
            'study_year' => ['required', Rule::exists('resource_study_years', 'uuid')]
        ]);

        $resource = ResourceStudyYear::where('uuid', $request->study_year)->first();

        $studyYear->update([
            'name' => $request->name,
            'resource_study_year_id' => $resource->id
        ]);

        return redirect()->route('studyYear.index');
    }

    public function subjects(StudyYear $studyYear)
    {
        $Y = SchoolYearController::current_year();

        $subject_count = AcademicWorkload::where('school_year_id', $Y->id)->where('study_year_id', $studyYear->id)->count();

        if ($subject_count == 0 && NULL !== $Y->available) {
            /*
             * Create Study Year Subjects
             */
            $areas = ResourceArea::with([
                'subjects' =>
                fn ($s) => $s->where('school_year_id', $Y->id)
            ])->orderBy('specialty')->orderBy('name')->get();

            return view('logro.studyyear.subjects')->with([
                'Y' => $Y,
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
                ->whereHas('academicWorkload', $fn_study_year)
                ->with(['academicWorkload' => $fn_study_year]);

            $areas = ResourceArea::with(['subjects' => $fn_subjects])
                ->whereHas('subjects', $fn_subjects)
                ->orderBy('specialty')->orderBy('name')->get();

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
            ->with(['academicWorkload' => $fn_study_year]);

        $areas = ResourceArea::with(['subjects' => $fn_subjects])
            ->orderBy('specialty')->orderBy('name')->get();

        return view('logro.studyyear.subjects_edit')->with([
            'Y' => $Y,
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

            $explode = explode('~', $area_subject);
            $area = $explode[0];
            $subject = $explode[1];
            $exist = @$explode[2];

            array_push($areas, $area);

            $hours_week = $subject . '~hours_week';
            $course_load = $subject . '~course_load';

            if ( empty($request->$course_load) ) {
                DB::rollBack();
                return redirect()->back()->withErrors(__("workload empty"));
            }
            if ( $request->$hours_week < 0 ) {
                DB::rollBack();
                return redirect()->back()->withErrors(__("there are negative hours"));
            }

            if ($request->$course_load > 100) {
                DB::rollBack();
                return redirect()->back()->withErrors(__("academic load must not exceed 100%"));
            }

            $total_course_load += $request->$course_load;

            if ('null' !== $exist && isset($exist)) {
                /*
                 * AcademicWorkload modified
                */
                $sy_subject = AcademicWorkload::where('id', $exist)
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
                 * AcademicWorkload Created
                */
                AcademicWorkload::create([
                    'school_year_id' => $Y->id,
                    'study_year_id' => $studyYear->id,
                    'subject_id' => $subject,
                    'hours_week' => $request->$hours_week,
                    'course_load' => $request->$course_load
                ]);
            }
        }

        $areas_total = count(array_unique($areas)) * 100;

        if ($total_course_load > $areas_total) {
            DB::rollBack();
            return redirect()->back()->withErrors(__("check the course load"));
        }

        DB::commit();
        Notify::success(__('Updated!'));
        return redirect()->route('studyYear.subject.show', $studyYear);
    }


    /*
     *
     *
     *  */
    public function download_guide_groups(StudyYear $studyYear)
    {
        $currentYear = SchoolYearController::current_year();

        $groups = $studyYear->groups->where('school_year_id', $currentYear->id)->pluck('id')->toArray();

        $TSG = TeacherSubjectGroup::whereIn('group_id', $groups)->get();


        /* Dirección para guardar los reportes generados */
        $pathUuid = Str::uuid();
        $pathReport = "reports/". $pathUuid ."/";

        if (!File::isDirectory(public_path('app/'.$pathReport))) {
            File::makeDirectory(public_path('app/'.$pathReport), 0755, true, true);
        }

        foreach($TSG as $teacherSubjectGroup)
        {
            Excel::store(
                new GroupStudentListGuide($teacherSubjectGroup),
                $pathReport . __('auxiliary template') .'_'. $teacherSubjectGroup->subject->resourceSubject->name .'_'. $teacherSubjectGroup->group->headquarters->name .'_'. $teacherSubjectGroup->group->studyTime->name .'_'. $teacherSubjectGroup->group->name .'_'. $teacherSubjectGroup?->teacher?->getFullName() . '.xlsx',
                'public'
            );
        }

        /* Generate Zip and Download */
        return (new ZipController($pathUuid))->downloadAllGuideGroups( Str::slug($studyYear->name) );
    }
    /*
     *
     *
     *  */
}
