<?php

namespace App\Http\Controllers;

use App\Http\Controllers\support\Notify;
use App\Http\Middleware\OnlyTeachersMiddleware;
use App\Models\Attendance;
use App\Models\AttendanceStudent;
use App\Models\TeacherSubjectGroup;
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
            'studentsAttendance' => ['required', 'array']
        ]);

        DB::beginTransaction();

        $attendance = Attendance::create(['teacher_subject_group_id' => $subject->id]);

        $studentsGroup = $subject->group->groupStudents;
        foreach ($studentsGroup as $studentG) {

            $attStudent = in_array($studentG->student->code, array_keys($request->studentsAttendance))
                ? 'Y'
                : 'N';

            AttendanceStudent::create([
                'attendance_id' => $attendance->id,
                'student_id' => $studentG->student->id,
                'attend' => $attStudent
            ]);
        }

        DB::commit();

        Notify::success(__('Attendance saved!'));
        return redirect()->back();
    }

    public function absences_view(Request $request)
    {
        $request->validate(['attendance' => ['required', Rule::exists('attendances', 'id')]]);

        $attendance = Attendance::find($request->attendance);

        $title = __('Attendance') .': '. $attendance->created_at;

        $content = '<table class="table table-striped mb-0"><tbody>';

        foreach ($attendance->absences as $absence) {
            $content .= '<tr><td scope="row">';
            if ($absence->attend === 'JUSTIFIED') {
                $content .= '<span class="badge bg-outline-success">'. __('justified') .'</span>';
            }
            $content .= $absence->student->getCompleteNames();
            $content .= $absence->student->tag();
            $content .= '</td></tr>';
        }

        $content .= '</tbody></table>';

        return ['title' => $title, 'content' => $content];
    }
}
