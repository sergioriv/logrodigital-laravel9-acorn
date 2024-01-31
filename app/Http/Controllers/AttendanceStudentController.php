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
    }

    public function subject(TeacherSubjectGroup $subject, Request $request)
    {
        $request->validate([
            'date' => ['required', 'date', 'date_format:Y-m-d', 'before:tomorrow'],
            'studentsAttendance' => ['nullable', 'array'],
            'studentsAttendance.*.type' => ['in:late-arrival,justified']
        ]);

        // $limitWeek = (new TeacherController)->remainingAttendanceWeek($subject, $request->date);
        // if ( ! $limitWeek ) {
        //     Notify::fail(__('Not allowed'));
        //     return back();
        // } else {
        //     if ( ! $limitWeek['active'] ) {
        //         Notify::fail(__("No assistance is available for that week."));
        //         return back();
        //     }
        // }

        DB::beginTransaction();

        $attendance = Attendance::create([
            'teacher_subject_group_id' => $subject->id,
            'date' => $request->date
        ]);

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

        $title = __('Attendance') . ': ' . $attendance->date;

        $content = '<table class="table table-striped mb-0"><tbody>';

        foreach ($attendanceStudents as $attStudent) {
            $content .= '<tr><td scope="row">';
            if ($attStudent->oneAttendanceStudent->attend->isJustified()) {
                $content .= '<span class="badge bg-outline-success me-1">' . __('Justified') . '</span>';
            } elseif ($attStudent->oneAttendanceStudent->attend->isLateArrival()) {
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
