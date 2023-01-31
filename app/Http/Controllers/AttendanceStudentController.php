<?php

namespace App\Http\Controllers;

use App\Http\Controllers\support\Notify;
use App\Http\Middleware\OnlyTeachersMiddleware;
use App\Models\Attendance;
use App\Models\AttendanceStudent;
use App\Models\Student;
use App\Models\TeacherSubjectGroup;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AttendanceStudentController extends Controller
{
    public function __construct()
    {
        $this->middleware(OnlyTeachersMiddleware::class);
    }

    public function subject(TeacherSubjectGroup $subject, Request $request)
    {
        $request->validate([
            'studentsAttendance' => ['nullable', 'array'],
            'studentsAttendance.*.type' => ['in:late-arrival,justified']
        ]);

        DB::beginTransaction();

        $attendance = Attendance::create(['teacher_subject_group_id' => $subject->id]);

        $studentsGroup = $subject->group->groupStudents;
        $studentsAttendace = [];

        foreach ($studentsGroup->pluck('student.id', 'student.code')->toArray() as $code => $id) {

            if (!$request->has('studentsAttendance') || !in_array($code, array_keys($request->studentsAttendance))) {
                $attStudent = 'N';
            } elseif (is_array($request->studentsAttendance[$code])) {
                if ($request->studentsAttendance[$code]['type'] === 'late-arrival') {
                    $attStudent = 'L';
                } elseif ($request->studentsAttendance[$code]['type'] === 'justified') {
                    $attStudent = 'J';
                }
            } else {
                $attStudent = 'Y';
            }

            array_push($studentsAttendace, new AttendanceStudent([
                'attendance_id' => $attendance->id,
                'student_id' => $id,
                'attend' => $attStudent
            ]));

        }

        $attendance->students()->saveMany($studentsAttendace);

        DB::commit();

        Notify::success(__('Attendance saved!'));
        return redirect()->back();
    }

    public function absences_view(Request $request)
    {
        $request->validate(['attendance' => ['required', Rule::exists('attendances', 'id')]]);

        $attendance = Attendance::find($request->attendance);

        $attendanceStudents = Student::withWhereHas(
                'oneAttendanceStudent',
                fn ($att) => $att->where('attendance_id', $attendance->id)->whereIn('attend', ['N', 'J', 'L'])
            )->get();

        $title = __('Attendance') . ': ' . $attendance->created_at;

        $content = '<table class="table table-striped mb-0"><tbody>';

        foreach ($attendanceStudents as $attStudent) {
            $content .= '<tr><td scope="row">';
            if ($attStudent->oneAttendanceStudent->attend === 'J') {
                $content .= '<span class="badge bg-outline-success me-1">' . __('Justified') . '</span>';
            } elseif ($attStudent->oneAttendanceStudent->attend === 'L') {
                $content .= '<span class="badge bg-outline-success me-1">' . __('Late arrival') . '</span>';
            }
            $content .= $attStudent->getCompleteNames();
            $content .= $attStudent->tag();
            $content .= '</td></tr>';
        }

        $content .= '</tbody></table>';

        return ['title' => $title, 'content' => $content];
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
                $attStudent->attend = $newAttend;
                $attStudent->save();
            }
        }

        DB::commit();

        Notify::success(__('Updated attendance!'));
        return redirect()->back();
    }
}
