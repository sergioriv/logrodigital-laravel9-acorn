<?php

namespace App\Http\Controllers;

use App\Http\Controllers\support\Notify;
use App\Models\Grade;
use App\Models\Group;
use App\Models\GroupStudent;
use App\Models\Period;
use App\Models\ResourceStudyYear;
use App\Models\Student;
use App\Models\StudentFile;
use App\Models\StudentReportBook;
use App\Models\StudyYear;
use Illuminate\Http\Request;

class GroupFinishController extends Controller
{
    private $countAreas;

    public function show(Group $group)
    {
        $Y = SchoolYearController::current_year();
        $groupStudents = GroupStudent::whereHas('group', fn($g) => $g->where('school_year_id', $Y->id)->where('id', $group->id))->with('student:id,first_name,second_name,first_last_name,second_last_name,inclusive,status,enrolled')->get();

        $minimalGrade = ($group->studyTime->low_performance + $group->studyTime->step);

        $areasWithSubjects = GradeController::teacher_subject($Y, $group);
        $this->countAreas = $areasWithSubjects->count();

        $periods = Period::where('school_year_id', $group->school_year_id)
                ->where('study_time_id', $group->study_time_id)
                ->orderBy('ordering')->get();

        $groupStudents->map(function ($groupStudentMap) use ($group, $Y, $areasWithSubjects, $periods, $minimalGrade) {

            if (!$group->studyYear->useGrades()) {
                $groupStudentMap->approved = TRUE;
                return $groupStudentMap;
            }

            $lossesArea = 0;

            $existGroupSpecialty = Group::where('study_year_id', $group->study_year_id)
                ->where('headquarters_id', $group->headquarters_id)
                ->where('study_time_id', $group->study_time_id)
                ->where('specialty', 1)
                ->whereHas('groupStudents', function ($query) use ($groupStudentMap) {
                    return $query->where('student_id', $groupStudentMap->student_id);
                })
                ->whereNotNull('specialty_area_id')->first();

            if ($existGroupSpecialty) {
                $areaSpecialty = GradeController::teacher_subject($Y, $existGroupSpecialty)->first();
                $areasWithSubjects[$this->countAreas] = $areaSpecialty;
            } else {
                unset($areasWithSubjects[$this->countAreas]);
            }


            $grades = Grade::where('student_id', $groupStudentMap->student_id)
                ->whereHas('period', fn($p) => $p->where('school_year_id', $Y->id))
                ->get();


            foreach ($areasWithSubjects as $area) {
                $totalArea = \App\Http\Controllers\GradeController::areaNoteStudent($groupStudentMap->student_id, $area, $periods, $grades, $group->studyTime);
                if ($totalArea['total'] < $minimalGrade && !$area->last ) { $lossesArea++; }
            }


            $groupStudentMap->lossesArea = $lossesArea;
            return $groupStudentMap;
        });

        return view('logro.finish.show', [
            'title' => "Cierre de grupo",
            'group' => $group,
            'groupStudents' => $groupStudents
        ]);
    }

    public function store(Group $group)
    {
        $Y = SchoolYearController::current_year();

        $nextResourceStudyYear = ResourceStudyYear::where('uuid', $group->studyYear->resource->next_year)->first()?->id;
        $nextStudyYear = StudyYear::where('resource_study_year_id', $nextResourceStudyYear)->first();


        $groupStudents = GroupStudent::whereHas('group', fn($g) => $g->where('school_year_id', $Y->id)->where('id', $group->id))->with('student:id,first_name,second_name,first_last_name,second_last_name,inclusive,status,enrolled')->get();

        $minimalGrade = ($group->studyTime->low_performance + $group->studyTime->step);

        $areasWithSubjects = GradeController::teacher_subject($Y, $group);
        $this->countAreas = $areasWithSubjects->count();

        $periods = Period::where('school_year_id', $group->school_year_id)
                ->where('study_time_id', $group->study_time_id)
                ->orderBy('ordering')->get();

        $groupStudents->map(function ($groupStudentMap) use ($group, $Y, $nextStudyYear, $areasWithSubjects, $periods, $minimalGrade) {

            if (!$group->studyYear->useGrades()) {
                $this->restartStudentForNextYear($group, $groupStudentMap->student, $Y, $nextStudyYear, TRUE);
                return $groupStudentMap;
            }

            $lossesArea = 0;

            $existGroupSpecialty = Group::where('study_year_id', $group->study_year_id)
                ->where('headquarters_id', $group->headquarters_id)
                ->where('study_time_id', $group->study_time_id)
                ->where('specialty', 1)
                ->whereHas('groupStudents', function ($query) use ($groupStudentMap) {
                    return $query->where('student_id', $groupStudentMap->student_id);
                })
                ->whereNotNull('specialty_area_id')->first();

            if ($existGroupSpecialty) {
                $areaSpecialty = GradeController::teacher_subject($Y, $existGroupSpecialty)->first();
                $areasWithSubjects[$this->countAreas] = $areaSpecialty;
            } else {
                unset($areasWithSubjects[$this->countAreas]);
            }


            $grades = Grade::where('student_id', $groupStudentMap->student_id)
                ->whereHas('period', fn($p) => $p->where('school_year_id', $Y->id))
                ->get();


            foreach ($areasWithSubjects as $area) {
                $totalArea = \App\Http\Controllers\GradeController::areaNoteStudent($groupStudentMap->student_id, $area, $periods, $grades, $group->studyTime);
                if ($totalArea['total'] < $minimalGrade && !$area->last ) { $lossesArea++; }
            }

            $this->restartStudentForNextYear($group, $groupStudentMap->student, $Y, $nextStudyYear, $lossesArea >= 1 ? FALSE : TRUE);

            $groupStudentMap->lossesArea = $lossesArea;
            return $groupStudentMap;
        });

        $group->update(['finish' => TRUE]);

        Notify::success('Grupo cerrado');
        return redirect()->route('group.show', $group->id);

    }

    private function restartStudentForNextYear(Group $group, Student $student, $Y, $nextStudyYear, $approval)
    {
        /* CREACION DEL RESULTADO ANUAL */
        $resultSchoolYear = \App\Models\ResultSchoolYear::updateOrCreate([
            'school_year_id' => $Y->id,
            'student_id' => $student->id
        ], [
            'result' => $approval
        ]);
        $resultSchoolYear->save();


        if ($approval) {

            $student->forceFill([
                'study_year_id' => $nextStudyYear->id ?? NULL,
                'enrolled' => NULL,
                'enrolled_date' => NULL,
                'group_id' => NULL,
                'group_specialty_id' => NULL,
                'status' => NULL,
                'wizard_documents' => NULL,
                'wizard_report_books' => NULL,
                'wizard_person_charge' => NULL,
                'wizard_personal_info' => NULL,
                'wizard_complete' => NULL,
            ])->save();

        } else {

            $student->forceFill([
                'enrolled' => NULL,
                'enrolled_date' => NULL,
                'group_id' => NULL,
                'group_specialty_id' => NULL,
                'status' => 'repeat',
                'wizard_documents' => NULL,
                'wizard_report_books' => NULL,
                'wizard_person_charge' => NULL,
                'wizard_personal_info' => NULL,
                'wizard_complete' => NULL,
            ])->save();
            $student->user->update(['avatar' => null]);

            $reportBook = StudentReportBook::where('student_id', $student->id)->where('resource_study_year_id', $group->studyYear->resource_study_year_id)->first();
            if ($reportBook) {
                StudentReportBookController::fileDelete($reportBook);
            }

        }

        $files = StudentFile::where('student_id', $student->id)->whereIn('student_file_type_id', [

            // Carnet EPS o certificado FOSYGA
            5,

            // Diagnóstico en caso de enfermedad
            7,

            // Seguro escolar o carta de desistimiento
            8,

            // Foto para documento con uniforme del colegio
            9,

            // Certificado médico
            13

        ])->get();

        foreach ($files as $file) {
            StudentFileController::fileDelete($file);
        }


    }

}
