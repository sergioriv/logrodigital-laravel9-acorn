<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\Group;
use App\Models\Period;
use App\Models\ResourceStudyYear;
use App\Models\ResultSchoolYear;
use App\Models\Student;
use App\Models\StudyYear;
use Barryvdh\DomPDF\Facade\Pdf;
use iio\libmergepdf\Merger;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CertificateFinalController extends Controller
{
    public function make(Request $request)
    {
        $merge = new Merger();

        $Y = SchoolYearController::current_year();
        $SCHOOL = SchoolController::myschool()->getData();

        $hq = $request->has('hq') ? explode(',', $request->get('hq')) : [];
        $st = $request->has('st') ? explode(',', $request->get('st')) : [];
        $sy = $request->has('sy') ? explode(',', $request->get('sy')) : [];

        if (!$hq && !$st && !$sy) return back();
        // Principal 1
        // Corzo 2
        // San Jose 3

        // Mañana 1
        // Tarde 2
        // Noche 3,4,7

        // secundaria 7,8,9,10
        // media 11,12

        $groups = Group::query()
        ->where('school_year_id', $Y->id)

        ->when($hq, fn($whenHQ) => $whenHQ->whereIn('headquarters_id', $hq))
        ->when($st, fn($whenST) => $whenST->whereIn('study_time_id', $st))
        ->when($sy, fn($whenSY) => $whenSY->whereIn('study_year_id', $sy))

        // Corzo
        // ->where('headquarters_id', 2)

        // San José
        // ->where('headquarters_id', 3)

        // Secundaria
        // ->where('headquarters_id', 1)
        // ->whereIn('study_time_id', [1,2])
        // ->whereIn('study_year_id', [7,8,9,10])

        // Media
        // ->where('headquarters_id', 1)
        // ->whereIn('study_time_id', [1,2])
        // ->whereIn('study_year_id', [11,12])

        // Nocturna
        // ->where('headquarters_id', 1)
        // ->whereIn('study_time_id', [3,4,7])

        ->where('finish', TRUE)
        ->whereNull('specialty')
        ->orderBy('headquarters_id')
        ->orderBy('study_time_id')
        ->orderBy('study_year_id')
        ->orderBy('name')
        ->get();

        $folio = 0;
        foreach ($groups as $group) {

            $students = Student::singleData()
            ->whereHas('groupStudents', fn($gs) => $gs->where('group_id', $group->id))
            ->orderBy('first_last_name')
            ->orderBy('second_last_name')
            ->orderBy('first_name')
            ->orderBy('second_name')
            ->get();

            $areasWithSubjects = GradeController::teacher_subject($Y, $group);
            $countAreas = count($areasWithSubjects);

            $periods = Period::where('school_year_id', $group->school_year_id)
                ->where('study_time_id', $group->study_time_id)
                ->orderBy('ordering')->get();
            $periodsPluck = $periods->pluck('id')->toArray();

            if (!is_null($group->studyYear->resource->next_year)) {
                $nextResourceStudyYear = ResourceStudyYear::where('uuid', $group->studyYear->resource->next_year)->first()?->id;
                $nextStudyYear = StudyYear::where('resource_study_year_id', $nextResourceStudyYear)->first();
            } else { $nextStudyYear = null; }


            foreach ($students as $student) {

                $existGroupSpecialty = Group::where('study_year_id', $group->study_year_id)
                    ->where('headquarters_id', $group->headquarters_id)
                    ->where('study_time_id', $group->study_time_id)
                    ->where('specialty', 1)
                    ->whereHas('groupStudents', function ($query) use ($student) {
                        return $query->where('student_id', $student->id);
                    })
                    ->whereNotNull('specialty_area_id')->first();

                // SPECIALTY
                if ($existGroupSpecialty) { $areasWithSubjects[$countAreas] = GradeController::teacher_subject($Y, $existGroupSpecialty)->first(); }
                else { unset($areasWithSubjects[$countAreas]); }

                // GRADES
                $grades = Grade::where('student_id', $student->id)
                    ->whereIn('period_id', $periodsPluck)
                    ->get();

                // NEXT YEAR
                $reportYear = ResultSchoolYear::where('student_id', $student->id)->where('school_year_id', $group->school_year_id)->first();

                // MERGE
                $pdf = $this->pdf($SCHOOL, (++$folio), $student, $reportYear, $nextStudyYear, $areasWithSubjects, $group, $periods, $grades, $group?->studyTime);
                $merge->addRaw($pdf);

            }

        }

        $nameFile = 'app/' . Str::uuid() . '.pdf';

        file_put_contents($nameFile, $merge->merge());

        return response()->download(
                public_path($nameFile),
                'certificado-final.pdf'
            )->deleteFileAfterSend();
    }


    private function pdf($SCHOOL, $folio, $student, $reportYear, $nextStudyYear, $areasWithSubjects, $group, $periods, $grades, $studyTime) {

        return Pdf::loadView('logro.pdf.certificate-notes-final', [
            'SCHOOL' => $SCHOOL,
            'folio' => $folio,
            'date' => now()->format('d/m/Y'),
            'reportYear' => $reportYear,
            'nextStudyYear' => $nextStudyYear,
            'student' => $student,
            'areas' => $areasWithSubjects,
            'group' => $group,
            'periods' => $periods,
            'grades' => $grades,
            'studyTime' => $studyTime,
        ])->setPaper('letter')->setOption('dpi', 72)->output();

    }
}
