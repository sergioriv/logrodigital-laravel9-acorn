<?php

namespace App\Http\Controllers;

use App\Http\Controllers\support\Notify;
use App\Models\Group;
use App\Models\GroupStudent;
use App\Models\Headquarters;
use App\Models\Student;
use App\Models\StudyTime;
use App\Models\StudyYear;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TransferController extends Controller
{
    private $students;

    public function __construct()
    {

    }
    public function groupStudents(Group $group)
    {
        $studentsGroup = Student::singleData()->whereHas('groupYear', fn ($GS) => $GS->where('group_id', $group->id))
            ->get();

        return view('logro.group.transfer-students', [
            'group' => $group,
            'students' => $studentsGroup
        ]);
    }

    public function groupStudents_selection(Request $request, Group $group)
    {

        $Y = SchoolYearController::current_year();

        $countGroups = Group::where('school_year_id', $Y->id)
            ->where('headquarters_id', $group->headquarters_id)
            ->where('study_time_id', $group->study_time_id)
            ->where('study_year_id', $group->study_year_id)
            ->count();


        $students = "";
        foreach ($request->students as $student) {
            $students .= $student.',';
        }

        $students = substr($students, 0, -1);

        return view('logro.group.transfer-hss', [
            'headquarters' => Headquarters::all(),
            'studyTime' => StudyTime::all(),
            'studyYear' => StudyYear::all(),
            'countGroups' => $countGroups,
            'group' => $group,
            'students' => $students
        ]);
    }

    public function groupStudents_hss(Request $request, Group $group)
    {
        $request->validate([
            'students' => ['required'],
            'headquarters' => ['required', Rule::exists('headquarters', 'id')],
            'studyTime' => ['required', Rule::exists('study_times', 'id')],
            'studyYear' => ['required', Rule::exists('study_years', 'id')],
        ]);

        $Y = SchoolYearController::current_year();


        $groups = Group::whereNull('specialty')
            ->where('school_year_id', $Y->id)
            ->where('headquarters_id', $group->headquarters_id)
            ->where('study_time_id', $group->study_time_id)
            ->where('study_year_id', $group->study_year_id)
            ->withCount('groupStudents as student_quantity')
            ->with('headquarters', 'studyTime', 'studyYear', 'teacher')
            ->with(['groupStudents' => fn($GS) => $GS->with('student')])
            ->get();

        if (0 === count($groups)) {
            Notify::fail(__('No groups'));
            return redirect()->back();
        }


            return view('logro.group.transfer-selGroup', [
                'groups' => $groups,
                'students' => $request->students,
            ]);


    }

    public function selectionGroup(Request $request)
    {
        $request->validate([
            'students' => ['required'],
            'group' => ['required', Rule::exists('groups', 'id')]
        ]);


        $students = explode(',', $request->students);

        foreach ($students as $stu) {

            $student = Student::find($stu);

            $groupStudentExist = GroupStudent::where('group_id', $request->group)->where('student_id', $student->id)->first();

            if(is_null($groupStudentExist)) {

                GroupStudent::create([
                    'group_id' => $request->group,
                    'student_id' => $student->id
                ]);

            } else {

                $groupStudentExist->update([
                    'group_id' => $request->group
                ]);
            }

            $student->update([
                'group_id' => $request->group,
                'enrolled_date' => now(),
                'enrolled' => TRUE
            ]);

        }

        Notify::success(__('Transfer students!'));
        return redirect()->route('group.show', $request->group);
    }
}
