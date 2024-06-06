<?php

namespace App\Http\Controllers;

use App\Exports\RecordAttendanceStudent;
use App\Http\Controllers\support\Notify;
use App\Http\Middleware\OnlyTeachersMiddleware;
use App\Models\Attendance;
use App\Models\AttendanceStudent;
use App\Models\Student;
use App\Models\TeacherSubjectGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class AttendanceStudentController extends Controller
{
    public function __construct()
    {
        $this->middleware('hasroles:SUPPORT,TEACHER')->only('subject','absences_view','absences_edit','absences_update');
        $this->middleware('hasroles:SUPPORT,TEACHER,COORDINATOR')->only('subject','absences_view', 'attendance_student_update');
    }

    public function subject(TeacherSubjectGroup $subject, Request $request)
    {
        $Y = SchoolYearController::current_year();
        $totalHoursWeek = \App\Models\AcademicWorkload::where('school_year_id', $Y->id)->where('study_year_id', $subject->group->study_year_id)->where('subject_id', $subject->subject_id)->sum('hours_week');

        $request->validate([
            'date' => ['required', 'date', 'date_format:Y-m-d', 'before:tomorrow'],
            'hours' => ['required', 'integer', 'min:1', 'max:'.$totalHoursWeek],
            'studentsAttendance' => ['nullable', 'array'],
            'studentsAttendance.*.type' => ['in:late-arrival,justified']
        ], [], ['hours' => 'horas']);

        $studentsAttendance = $request->get('studentsAttendance');
        $totalHours = $request->get('hours') ?? 1;

        $students = \App\Models\Student::select('id', 'code')
        ->whereHas('groupYear', fn($group) => $group->where('group_id', $subject->group_id))->orderBy('first_last_name')
        ->orderBy('second_last_name')
        ->orderBy('first_name')
        ->orderBy('second_name')
        ->get()->pluck('id', 'code')->toArray();

        $dataAttendanceStudents = [];

        foreach ($students as $code => $id) {

            if ( !array_key_exists($code, $studentsAttendance) || in_array($studentsAttendance[$code], ['late-arrival', 'justified']) ) {

                $attend = \App\Enums\AttendStudentEnum::NO;
                if (array_key_exists($code, $studentsAttendance)) {
                    $studentsAttendance[$code] === 'late-arrival' && $attend = \App\Enums\AttendStudentEnum::LATE_ARRIVAL;
                    $studentsAttendance[$code] === 'justified' && $attend = \App\Enums\AttendStudentEnum::JUSTIFIED;
                }

            } else {

                $attend = \App\Enums\AttendStudentEnum::YES;

            }

            array_push($dataAttendanceStudents,[
                'student_id' => $id,
                'attend' => $attend
            ]);
        }

        DB::beginTransaction();

        $attendance = Attendance::create([
            'teacher_subject_group_id' => $subject->id,
            'date' => $request->date,
            'hours' => $totalHours
        ]);

        $attendance->students()->saveMany(
            array_map(function ($attendanceStudent) use ($attendance) {
                $attendanceStudent['attendance_id'] = $attendance->id;
                return new AttendanceStudent($attendanceStudent);
            }, $dataAttendanceStudents)
        );

        DB::commit();

        Notify::success(__('Attendance saved!'));
        return redirect()->back();
    }

    public function absences_view(Request $request)
    {
        $request->validate(['attendance' => ['required', Rule::exists('attendances', 'id')]]);

        $attendance = Attendance::find($request->attendance);

        // TODO: listar por attendance student
        $attendanceStudents = \App\Models\AttendanceStudent::where('attendance_id', $attendance->id)
        ->with('student:id,first_name,second_name,first_last_name,second_last_name,inclusive,status')
        ->whereIn('attend', ['N', 'J', 'L'])->get();

        $title = __('Attendance') . ': ' . $attendance->date
            .'<br /><span clasS="badge bg-primary font-standard font-weight-300 mt-1 text-medium">Horas: '
            . $attendance->hours
            .'</span>';

        $content = '<table class="table table-striped mb-0"><tbody>';

        foreach ($attendanceStudents as $attStudent) {
            $content .= '<tr><td scope="row">';
            if ($attStudent->attend->isJustified()) {
                $content .= '<span class="badge bg-outline-success me-1">' . __('Justified') . '</span>';
            } elseif ($attStudent->attend->isLateArrival()) {
                $content .= '<span class="badge bg-outline-success me-1">' . __('Late arrival') . '</span>';
            }
            $content .= $attStudent->student->getCompleteNames();
            $content .= $attStudent->student->tag();
            $content .= '</td></tr>';
        }

        $content .= '</tbody></table>';

        return ['title' => $title, 'content' => $content];
    }

    public function attendance_student_update(Request $request)
    {
        $request->validate([
            'attendance-change-id' => ['required'],
            'attendance-new-type' => ['required', 'in:yes,no,late-arrival,justified']
        ]);

        DB::beginTransaction();

        $attendanceStudent = AttendanceStudent::find($request->get('attendance-change-id'));

        if (!$attendanceStudent) return back();

        try {
            $newAttend = match($request->get('attendance-new-type')) {
                'yes' => \App\Enums\AttendStudentEnum::YES,
                'no' => \App\Enums\AttendStudentEnum::NO,
                'late-arrival' => \App\Enums\AttendStudentEnum::LATE_ARRIVAL,
                'justified' => \App\Enums\AttendStudentEnum::JUSTIFIED,
                default => null
            };

            if (is_null($newAttend)) return back();

            $attendanceStudent->update(['attend' => $newAttend]);

        } catch (\Throwable $th) {
            DB::rollBack();
            Notify::fail(__('Something went wrong.'));
            return back();
        }

        DB::commit();

        Notify::success(__('Attendance saved!'));
        return back();
    }

    public function absences_edit(Attendance $attendance)
    {
        $attendanceStudents = Student::singleData()->withWhereHas(
                'oneAttendanceStudent',
                fn ($att) => $att->where('attendance_id', $attendance->id)
            )->get();

        $content = view('components.attendances.edit', [
                'attendance' => $attendance,
                'students' => $attendanceStudents
            ])->render();

        return $content;
    }

    public function absences_update(Request $request, Attendance $attendance)
    {
        $request->validate([
            'studentsAttendance' => ['nullable', 'array'],
            'studentsAttendance.*.type' => ['in:late-arrival,justified']
        ]);

        DB::beginTransaction();

        $attendanceStudent = AttendanceStudent::where('attendance_id', $attendance->id)
            ->with('student')
            ->get();

        foreach ($attendanceStudent as $attStudent) {

            if (!$request->has('studentsAttendance') || !in_array($attStudent->student->code, array_keys($request->studentsAttendance))) {
                $newAttend = 'N';
            } elseif (is_array($request->studentsAttendance[$attStudent->student->code])) {
                if ($request->studentsAttendance[$attStudent->student->code]['type'] === 'late-arrival') {
                    $newAttend = 'L';
                } elseif ($request->studentsAttendance[$attStudent->student->code]['type'] === 'justified') {
                    $newAttend = 'J';
                }
            } else {
                $newAttend = 'Y';
            }

            /* verifica si es diferente para ser actualizada */
            if ($attStudent->attend !== $newAttend) {
                DB::update("UPDATE attendance_students SET attend = ? WHERE attendance_id = ? AND student_id = ?", [
                    $newAttend,
                    $attendance->id,
                    $attStudent->student->id
                ]);
            }
        }

        DB::commit();

        Notify::success(__('Updated attendance!'));
        return redirect()->back();
    }

    public function reportForStudent(Student $student)
    {
        return Excel::download(new RecordAttendanceStudent($student), 'Fallas - ' . $student->getCompleteNames() . '.xlsx');
    }


    public function upload_file(Request $request)
    {
        $request->validate([
            'attendance-file-id' => ['required'],
            'attendance-file-student' => ['required'],
            'file_attendance' => ['required', 'file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:2048']
        ]);

        if ($request->hasFile('file_attendance')) {
            $pathAux = $request->file('file_attendance')->store('students/' . $request->get('attendance-file-student') . '/absences', 'public');
            $path = $pathAux ? config('filesystems.disks.public.url') . '/' . $pathAux : null;
        } else $path = null;

        AttendanceStudent::where('attendance_id', $request->get('attendance-file-id'))->where('student_id', $request->get('attendance-file-student'))->update([
            'file_support' => $path
        ]);

        Notify::success(__('File upload!'));
        return back();
    }
}
