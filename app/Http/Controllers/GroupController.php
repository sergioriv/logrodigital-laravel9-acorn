<?php

namespace App\Http\Controllers;

use App\Exports\GroupStudentList;
use App\Exports\GroupStudentListGuide;
use App\Exports\StudentsWithFiles;
use App\Http\Controllers\Mail\SmtpMail;
use App\Http\Controllers\support\Notify;
use App\Http\Controllers\support\UserController;
use App\Http\Middleware\YearCurrentMiddleware;
use App\Models\AcademicWorkload;
use App\Models\AttendanceStudent;
use App\Models\Data\RoleUser;
use App\Models\Grade;
use App\Models\Group;
use App\Models\GroupStudent;
use App\Models\Headquarters;
use App\Models\Period;
use App\Models\ResourceArea;
use App\Models\Student;
use App\Models\StudyTime;
use App\Models\StudyYear;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\TeacherSubjectGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class GroupController extends Controller
{
    function __construct()
    {
        $this->middleware('can:groups.index')->only('index');
        $this->middleware('can:groups.create')->only('create', 'store', 'edit', 'update', 'delete');
        $this->middleware('can:groups.students.matriculate')->only('matriculate', 'matriculate_update');
        $this->middleware('can:groups.teachers.edit')->only('teacher_edit', 'teacher_update');

        $this->middleware(YearCurrentMiddleware::class)->except('index', 'filter', 'show', 'exportStudentList');
    }

    public function index()
    {
        $Y = SchoolYearController::current_year();

        $groups = Group::with('headquarters', 'studyTime', 'studyYear', 'teacher')
            ->withCount('groupStudents as student_quantity')
            ->where('school_year_id', $Y->id)
            ->orderBy('headquarters_id')
            ->orderBy('study_time_id')
            ->orderBy('study_year_id')
            ->orderBy('name')
            ->get();

        $headquarters = Headquarters::all();
        $studyTimes = StudyTime::all();
        $studyYears = StudyYear::all();

        return view('logro.group.index')->with([
            'Y' => $Y,
            'groups' => $groups,
            'headquarters' => $headquarters,
            'studyTimes' => $studyTimes,
            'studyYears' => $studyYears
        ]);
    }

    public function filter(Request $request)
    {
        $Y = SchoolYearController::current_year();

        $hq = $request->headquarters;
        $st = $request->studyTime;
        $sy = $request->studyYear;
        $name = $request->name;

        $groups = Group::with('headquarters', 'studyTime', 'studyYear', 'teacher')->where('school_year_id', $Y->id);

        if (NULL !== $hq)
            $groups->where('headquarters_id', $hq);

        if (NULL !== $st)
            $groups->where('study_time_id', $st);

        if (NULL !== $sy)
            $groups->where('study_year_id', $sy);

        if (NULL !== $name)
            $groups->where('name', 'like', '%' . $name . '%');

        $groups->withCount('groupStudents as student_quantity')
            ->orderBy('headquarters_id')
            ->orderBy('study_time_id')
            ->orderBy('study_year_id')
            ->orderBy('name');

        return $groups->get();
    }

    public function create()
    {

        $headquarters = Headquarters::where('available', TRUE)->get();
        $studyTime = StudyTime::all();
        $studyYear = StudyYear::all();
        $teachers = Teacher::select('uuid', 'names', 'last_names')->get();

        return view('logro.group.create')->with([
            'headquarters' => $headquarters,
            'studyTime' => $studyTime,
            'studyYear' => $studyYear,
            'teachers' => $teachers,
            'existAreasSpecialty' => Subject::whereHas('resourceArea', fn($ra) => $ra->where('specialty', 1))->count()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'headquarters'  => ['required', Rule::exists('headquarters', 'id')],
            'study_time'    => ['required', Rule::exists('study_times', 'id')],
            'study_year'    => ['required', Rule::exists('study_years', 'id')],
            'group_director' => ['nullable', Rule::exists('teachers', 'uuid')],
            'name'          => ['required', 'string'],
            'specialty'     => ['nullable', 'in:no,yes']
        ]);

        $Y = SchoolYearController::current_year();

        $uuidTeacher = Teacher::select('id')->find($request->group_director)->id ?? null;

        try {
            $newGroup = Group::create([
                'school_year_id' => $Y->id,
                'headquarters_id' => $request->headquarters,
                'study_time_id' => $request->study_time,
                'study_year_id' => $request->study_year,
                'teacher_id' => $uuidTeacher,
                'name' => $request->name,
                'specialty' => $request->specialty === 'yes' ? TRUE : NULL
            ]);
        } catch (\Throwable $th) {
            return redirect()->back()->withErrors(__('Unexpected Error'));
        }

        if ($request->specialty === 'yes') {
            return redirect()->route('group.specialty', $newGroup->id);
        }

        Notify::success(__('Group created!'));
        return redirect()->route('group.index');
    }

    public function specialty(Group $group)
    {
        if (is_null($group->specialty)) {
            return redirect()->route('group.index')->withErrors(__('Not allowed'));
        }


        $Y = SchoolYearController::current_year();

        if (NULL === $Y->available) {
            $resourceAreas = ResourceArea::where('specialty', 1)->whereHas('subjects', fn ($s) => $s->where('school_year_id', $Y->id))
                ->with(['subjects' => fn ($s) => $s->where('school_year_id', $Y->id)])
                ->orderBy('name')->get();
        } else {
            $resourceAreas = ResourceArea::where('specialty', 1)->with(['subjects' => fn ($s) => $s->where('school_year_id', $Y->id)])
                ->orderBy('name')->get();
        }

        return view('logro.group.specialty', [
            'group' => $group,
            'resourceAreas' => $resourceAreas
        ]);
    }

    public function specialty_store(Group $group, Request $request)
    {
        if (is_null($group->specialty)) {
            return redirect()->route('group.index')->withErrors(__('Not allowed'));
        }

        $request->validate([
            'area_specialty' => ['required', Rule::exists('resource_areas','id')->where('specialty', 1)]
        ]);

        $existSubjects = Subject::where('resource_area_id', $request->area_specialty)->count();
        if (!$existSubjects) {
            return redirect()->route('group.specialty', $group)->withErrors(__('Unexpected Error'));
        }


        $group->forceFill(['specialty_area_id' => $request->area_specialty])->save();

        Notify::success(__('Specialty group saved!'));
        return redirect()->route('group.index');

    }



    public function show(Group $group)
    {

        /*
         * Para que el Rol TEACHER no pueda acceder a ningun grupo que no sea director de grupo
         *  */
        if (RoleUser::TEACHER_ROL === UserController::role_auth()
            && !in_array($group->id, TeacherController::myDirectorGroup()->pluck('id')->toArray())) {

            return redirect()->route('teacher.my.subjects')->withErrors(__('Unauthorized!'));
        }


        $Y = SchoolYearController::current_year();
        $roleAuth = UserController::role_auth();


        $studentsGroup = Student::singleData()->whereHas('groupYear', fn($gr) => $gr->where('group_id', $group->id))
                ->with('groupOfSpecialty')
                ->get();

        $areas = $this->subjects_teacher($Y, $group);


        /* Para obtener el promedio de las notas generales del grupo */
        $teacherSubject = [];
        foreach ($areas as $area) {
            foreach ($area->subjects as $subject) {
                if (!is_null($subject->teacherSubject))
                    array_push($teacherSubject, $subject->teacherSubject->id);
            }
        }
        $avgGrade = Grade::whereIn('teacher_subject_group_id', $teacherSubject)->avg('final');

        $absences = AttendanceStudent::whereIn('attend', ['N', 'J', 'L'])
        ->withWhereHas(
            'attendance',
            fn ($attend) => $attend->whereIn('teacher_subject_group_id', $teacherSubject)
                ->with('teacherSubjectGroup.subject')
        )->with('student')
        ->orderByDesc('created_at')
        ->get();

        $countPeriods = Period::where('school_year_id', $Y->id)->where('study_time_id', $group->study_time_id)->count();
        $periods = Period::where('school_year_id', $Y->id)
            ->where('study_time_id', $group->study_time_id)
            ->when(RoleUser::TEACHER_ROL === $roleAuth, function ($query){
                return $query->with('remarks')->where('start', '>=', today()->format('Y-m-d'));
            }, function ($query) {
                return $query->where('end', '<=', today()->format('Y-m-d'));
            })
            ->orderBy('ordering')->get();


        return view('logro.group.show')->with([
            'Y' => $Y,
            'group' => $group,
            'count_studentsNoEnrolled' => $this->countStudentsNoEnrolled($Y, $group),
            'count_studentsMatriculateInStudyYear' => $this->countStudentMatriculateInStudyYear($Y, $group),
            'studentsGroup' => $studentsGroup,
            'areas' => $areas,
            'countPeriods' => $countPeriods,
            'periods' => $periods,
            'avgGrade' => $avgGrade,
            'absences' => $absences
        ]);
    }

    public function edit(Group $group)
    {
        $headquarters = Headquarters::where('available', TRUE)->get();
        $studyTime = StudyTime::all();
        $studyYear = StudyYear::all();
        $teachers = Teacher::select('id', 'uuid', 'names', 'last_names')
            ->orderBy('names')
            ->orderBy('last_names')
            ->get();

        return view('logro.group.edit')->with([
            'group' => $group,
            'headquarters' => $headquarters,
            'studyTime' => $studyTime,
            'studyYear' => $studyYear,
            'teachers' => $teachers
        ]);
    }

    public function update(Group $group, Request $request)
    {
        $request->validate([
            'headquarters' => ['required', Rule::exists('headquarters', 'id')],
            'study_time' => ['required', Rule::exists('study_times', 'id')],
            'study_year' => ['required', Rule::exists('study_years', 'id')],
            'group_director' => ['nullable', Rule::exists('teachers', 'uuid')],
            'name' => ['required', 'string']
        ]);

        $uuidTeacher = Teacher::select('id')->find($request->group_director)->id ?? null;

        $group->update([
            'headquarters_id' => $request->headquarters,
            'study_time_id' => $request->study_time,
            'study_year_id' => $request->study_year,
            'teacher_id' => $uuidTeacher,
            'name' => $request->name,
        ]);

        Notify::success(__('Group updated!'));
        return redirect()->route('group.show', $group);
    }

    public function matriculate(Group $group)
    {
        $Y = SchoolYearController::current_year();

        if (is_null($group->specialty)) {

            /* Estudiantes que estes sin matricula */
            $studentsForMatriculate = Student::select(
                'id',
                'first_name',
                'second_name',
                'first_last_name',
                'second_last_name',
                'document_type_code',
                'document',
                'inclusive',
                'status'
            )->where('school_year_create', '<=', $Y->id)
                ->where('headquarters_id', $group->headquarters_id)
                ->where('study_time_id', $group->study_time_id)
                ->where('study_year_id', $group->study_year_id)
                ->whereNull('enrolled')
                ->get();

            if (count($studentsForMatriculate) === 0) {
                Notify::fail(__('No students to enroll'));
                return redirect()->back();
            }
        } else {

            /* Estudiantes ya matriculados que se van a registrar en una especialidad */
            $studentsForMatriculate = Student::select(
                'id',
                'first_name',
                'second_name',
                'first_last_name',
                'second_last_name',
                'document_type_code',
                'document',
                'inclusive',
                'status',
                'group_id'
            )->whereHas('groupStudents',
                fn($gs) => $gs->whereHas('group',
                    fn($g) => $g->where('school_year_id', $Y->id)
                        ->where('headquarters_id', $group->headquarters_id)
                        ->where('study_time_id', $group->study_time_id)
                        ->where('study_year_id', $group->study_year_id)
                ))
                ->where('enrolled', 1)
                ->whereNull('group_specialty_id')
                ->get();

            if (count($studentsForMatriculate) === 0) {
                Notify::fail(__('No students to enroll'));
                return redirect()->back();
            }
        }

        return view('logro.group.matriculate')->with([
            'group' => $group,
            'studentsForMatriculate' => $studentsForMatriculate
        ]);
    }

    public function matriculate_update(Group $group, Request $request)
    {
        $request->validate([
            'students' => ['required', 'array']
        ]);

        DB::beginTransaction();

        foreach ($request->students as $student) {

            if ($group->specialty) {

                $studentNoNull = Student::where('id', $student)
                    ->where('headquarters_id', $group->headquarters_id)
                    ->where('study_time_id', $group->study_time_id)
                    ->where('study_year_id', $group->study_year_id)
                    ->where('enrolled', 1)
                    ->whereNotNull('group_id')
                    ->whereNull('group_specialty_id')->first();

            } else {

                $studentNoNull = Student::where('id', $student)
                    ->where('headquarters_id', $group->headquarters_id)
                    ->where('study_time_id', $group->study_time_id)
                    ->where('study_year_id', $group->study_year_id)
                    ->whereNull('enrolled')->first();
            }

            if (NULL !== $studentNoNull) {
                GroupStudent::create([
                    'group_id' => $group->id,
                    'student_id' => $student
                ]);

                /* si el grupo es de especialidad, ya debe estar matriculado en otro grupo
                 * y le será asignado un grupo de especialidad */
                if ($group->specialty) {

                    $studentNoNull->forceFill([
                        'group_specialty_id' => $group->id
                    ])->save();

                } else {

                    $studentNoNull->update([
                        'group_id' => $group->id,
                        'enrolled_date' => now(),
                        'enrolled' => TRUE
                    ]);
                }

                /* Send mail to Email Person Charge */
                SmtpMail::init()->sendEmailEnrollmentNotification($studentNoNull, $group);
            } else {

                DB::rollBack();
                return redirect()->back()->withErrors(__("Unexpected Error"));
            }
        }

        DB::commit();

        Notify::success(__('Students matriculate!'));
        return redirect()->route('group.show', $group);
    }

    public function teacher_edit(Group $group)
    {
        $Y = SchoolYearController::current_year();

        $teachers = Teacher::where('active', TRUE)
            ->orderBy('names')->orderBy('last_names')->get();

        $areas = $this->subjects_teacher($Y, $group);

        return view('logro.group.teachers_edit')->with([
            'Y' => $Y,
            'group' => $group,
            'areas' => $areas,
            'teachers' => $teachers
        ]);
    }

    public function teacher_update(Group $group, Request $request)
    {
        $Y = SchoolYearController::current_year();

        foreach ($request->teachers as $teacher_subject) {
            if (NULL !== $teacher_subject) {

                [$subject, $teacher] = explode('~', $teacher_subject);

                /*
                 * Para comprobar que la materia que llega, pertenece al grado que se encuentra el grado
                 *  */
                $checkSubjectInGroup = AcademicWorkload::where('school_year_id', $group->school_year_id)
                    ->where('study_year_id', $group->study_year_id)
                    ->where('subject_id', $subject)->first();
                if (is_null($checkSubjectInGroup)) {
                    break;
                }

                $uuidTeacher = Teacher::select('id')->find($teacher)->id ?? null;

                TeacherSubjectGroup::updateOrCreate(
                    [
                        'school_year_id' => $Y->id,
                        'group_id' => $group->id,
                        'subject_id' => $subject
                    ],
                    [
                        'teacher_id' => $uuidTeacher
                    ]
                );
            }
        }

        Notify::success(__('Group updated!'));
        return redirect()->route('group.show', $group);
    }

    private function subjects_teacher($Y, $group)
    {
        $fn_sy = fn ($sy) =>
        $sy->where('school_year_id', $Y->id)
            ->where('study_year_id', $group->study_year_id);

        $fn_tsg = fn ($tsg) =>
        $tsg->where('school_year_id', $Y->id)
            ->where('group_id', $group->id)
            ->with('teacher');

        $fn_sb = fn ($s) =>
        $s->where('school_year_id', $Y->id)
            ->withWhereHas('academicWorkload', $fn_sy)
            ->with('resourceSubject')
            ->with(['teacherSubject' => $fn_tsg]);



        /* ******************************* */



        /* $sy_id = $group->study_year_id;

        $fn_sy = fn ($sy) =>
        $sy->where('school_year_id', $Y_id)
            ->where('study_year_id', $sy_id);

        $fn_sb = fn ($s) =>
        $s->where('school_year_id', $Y_id)
            ->whereHas('academicWorkload', $fn_sy)
            ->with(['academicWorkload' => $fn_sy]); */

        if (is_null($group->specialty)) {

            return ResourceArea::whereNull('specialty')
                // ->with(['subjects' => $fn_sb])
                ->withWhereHas('subjects', $fn_sb)
                ->orderBy('name')->get();

        } else {

            if (is_null($group->specialty_area_id)) {
                return ResourceArea::whereNull('id')->get();
            }

            return ResourceArea::where('id', $group->specialty_area_id)
                // ->with(['subjects' => $fn_sb])
                ->withWhereHas('subjects', $fn_sb)
                ->orderBy('name')->get();

        }
    }

    public function delete(Request $request, Group $group)
    {
        if ($group->groupStudents->isEmpty()) {

            $group->delete();

            Notify::success(__('Group deleted!'));
            return redirect()->route('group.index');
        }

    }


    /* ADICIONALES */
    private function countStudentsNoEnrolled($Y, $group)
    {
        return Student::where('school_year_create', '<=', $Y->id)
            ->where('headquarters_id', $group->headquarters_id)
            ->where('study_time_id', $group->study_time_id)
            ->where('study_year_id', $group->study_year_id)
            ->whereNull('enrolled')
            ->count();
    }

    private function countStudentMatriculateInStudyYear($Y, $group)
    {
        if ($group->specialty) {

            return Student::whereHas('groupStudents',
                fn($gs) => $gs->whereHas('group',
                    fn($g) => $g->where('school_year_id', $Y->id)
                                ->where('headquarters_id', $group->headquarters_id)
                                ->where('study_time_id', $group->study_time_id)
                                ->where('study_year_id', $group->study_year_id)
                            ))->where('enrolled', 1)
                            ->whereNull('group_specialty_id')->count();
        }

        return null;
    }


    /* Export */
    public function exportStudentListGuide(TeacherSubjectGroup $subject)
    {
        return Excel::download(new GroupStudentListGuide($subject), __('auxiliary template') .'_'. $subject->subject->resourceSubject->name .'_'. $subject->group->name .'_'. $subject->teacher->getFullName() . '.xlsx');
    }
    public function exportStudentList(Group $group)
    {
        return Excel::download(new GroupStudentList($group), __('student list :GROUP', ['GROUP' => $group->name]) . '.xlsx');
    }

    public function exportStudentsWithFiles(Group $group)
    {
        $studentsGroup = Student::singleData()->whereHas('groupYear', fn($gr) => $gr->where('group_id', $group->id))
                ->get();

        return Excel::download(new StudentsWithFiles($studentsGroup), __('Information From Students in Group :GROUP', ['GROUP' => $group->name]) . '.xlsx');
    }
}
