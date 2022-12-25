<?php

namespace App\Jobs;

use App\Http\Controllers\GradeController;
use App\Http\Controllers\SchoolController;
use App\Models\Grade;
use App\Models\Group;
use App\Models\Period;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ReportGradeStudent implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $Y;
    private $group;
    private $studyTime;
    private $currentPeriod;
    private $periods;
    private $areasWithSubjects;
    private $student;
    public function __construct($Y, $group, $studyTime, $currentPeriod, $periods, $areasWithSubjects, $student)
    {
        $this->Y = $Y;
        $this->group = $group;
        $this->studyTime = $studyTime;
        $this->currentPeriod = $currentPeriod;
        $this->periods = $periods;
        $this->areasWithSubjects = $areasWithSubjects;
        $this->student = $student;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        /* Nombre para el reporte de notas, en caso de ser el reporte final, dirá Final */
        $titleReportNotes = 'P' . $this->currentPeriod->ordering . ' - ' . $this->Y->name;

        /* Si el estudiante pertenece a un grupo de especialidad */

        $existGroupSpecialty = Group::where('study_year_id', $this->group->study_year_id)
            ->where('headquarters_id', $this->group->headquarters_id)
            ->where('study_time_id', $this->group->study_time_id)
            ->where('specialty', 1)
            ->whereHas('groupStudents', function ($query) {
                return $query->where('student_id', $this->student->id);
            })
            ->whereNotNull('specialty_area_id')->first();



        // /* Obtiene las areas y asignaturas del grupo que corresponde */
        // $areasWithSubjects = $this->teacher_subject($Y, $group);

        /* Si el estudiante tiene un grupo de especialidad, se agregará a la lista general con su area y asignatura de especialidad */
        if ($existGroupSpecialty) {
            $this->areasWithSubjects->push((new GradeController)->teacher_subject($this->Y, $existGroupSpecialty)->first());
        }


        /* Notas del estudiante de los periodos y asignaturas del StudyYear actual */
        $grades = Grade::where('student_id', $this->student->id)
            ->whereIn('period_id', $this->periods->pluck('id'))
            ->get();


        $pdf = Pdf::loadView('logro.pdf.report-notes', [
            'SCHOOL' => SchoolController::myschool()->getData(),
            'date' => now()->format('d/m/Y'),
            'student' => $this->student,
            'areas' => $this->areasWithSubjects,
            'periods' => $this->periods,
            'currentPeriod' => $this->currentPeriod,
            'grades' => $grades,
            'group' => $this->group,
            'studyTime' => $this->studyTime,
            'titleReportNotes' => $titleReportNotes,
        ]);
        $pdf->setPaper('letter', 'portrait');
        $pdf->setOption('dpi', 72);

        $pdf->save('reports/Reporte de notas - '. $this->student->getCompleteNames() . '.pdf');
    }
}
