<?php

namespace App\Exports;

use App\Http\Controllers\GradeController;
use App\Models\Period;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class ConsolidateGeneralGradesGroup implements FromArray, WithColumnWidths, WithStyles, WithEvents
{
    private $group;
    private $schoolYear;
    private $ST;
    private $period;
    private $periods;
    private $areas;
    private $students;

    private $gradeController;
    private $spaceCellVoid = "  ";
    private $colAreas = [];
    private $colAreasSpecialty = [];
    private $lowGrades = [];
    private $colPeriodsAfter = [];

    public function __construct($group, $schoolYear, $ST, $period, $areas, $students)
    {
        $this->group = $group;
        $this->schoolYear = $schoolYear;
        $this->ST = $ST;
        $this->areas = $areas;
        $this->students = $students;

        $periods = \App\Models\Period::where('school_year_id', $schoolYear->id)->where('study_time_id', $ST->id)->when($period !== 'FINAL', fn($whenNoFinal) => $whenNoFinal->where('ordering', '<=', $period->ordering))->orderBy('ordering')->get();
        $this->period = $period;
        $this->periods = $periods;

        $this->gradeController = GradeController::class;
    }

    public function columnWidths(): array
    {
        // 10 => 70 puntos
        $widths = [
            'A' => 5,
            'B' => 60,
        ];
        $l = 'C';
        for ($i=1; $i < 300; $i++) {
            $widths[$l++] = 6;
        }
        foreach ($this->colAreas as $col) {
            $widths[++$col] = 2;
        }

        return $widths;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function array(): array
    {
        $headers = ['#', __('Full name')];
        $colAreas = 'B';

        if ($this->period === 'FINAL') { $periodsAfter = []; }
        else {
            $periodsAfter = Period::where('school_year_id', $this->schoolYear->id)->where('study_time_id', $this->ST->id)->where('ordering', '>', $this->period->ordering)->get();
        }
        $minimalGrade = ($this->group->studyTime->low_performance + $this->group->studyTime->step);
        $missingPorcentage = 100 - $this->periods->sum('workload');

        foreach ($this->areas as $area) {
            if ($this->period !== 'FINAL') foreach ($area['subjects'] as $key => $subject) {
                foreach ($this->periods as $period) {
                    $colAreas++;
                    $headers[] = ' P'. $period->ordering .' - '. $subject['resource_name'] . ' - ' . $subject['academic_workload'] . '%';
                }

                foreach ($periodsAfter as $periodAfter) {
                    $colAreas++;
                    $this->colPeriodsAfter[] = $colAreas;
                    $headers[] = ' P'. $periodAfter->ordering .' - Mínimo requerido';
                }
            }

            $colAreas++;

            $headers[] = " ACUM: " . $area['name'];
            $headers[] = $this->spaceCellVoid;
            $this->colAreas[] = $colAreas;
            if ($area['isSpecialty']) $this->colAreasSpecialty[] = $colAreas;

            $colAreas++;

        }
        if ($this->period !== 'FINAL') $headers[] = 'ASIGNATURAS PERIDIDAS P' . $this->period->ordering;
        $headers[] = 'ÁREAS PERDIDAS';
        if ($this->period !== 'FINAL') $headers[] = 'ASIGNATURAS EVALUADAS';
        $headers[] = 'PROMEDIO';
        $headers[] = 'PUESTO';

        $infoPeriods = '';
        if ($this->period !== 'FINAL') {
            foreach ($this->periods as $key => $period) {
                if ($key) $infoPeriods .= " | ";
                $infoPeriods .= "P{$period->ordering}: {$period->workload}%";
            }
        }

        $array = [
            [__('Group') . ': ' . $this->group->name],
            [__('Period') . ': ' . ($this->period === 'FINAL' ? 'FINAL' : $this->period->name)],
            [__('export.headquarters') . ': ' . $this->group->headquarters->name . ' | ' . __('export.study_time') . ': ' . $this->group->studyTime->name . ' | ' . __('export.study_year') . ': ' . $this->group->studyYear->name],
            [$infoPeriods],
            $headers
        ];

        $position = [];
        foreach ($this->students as $student) {
            $average = 0;
            foreach ($this->areas as $area) {
                $totalArea = 0;
                foreach ($area['subjects'] as $subject) {
                    $totalSubject = 0;

                    $existStudentLeveling = !is_null($subject['teacher_subject_group'])
                        ? \App\Models\LeveledStudent::where('teacher_subject_group_id', $subject['teacher_subject_group'])->where('student_id', $student->id)->count()
                        : 0;

                    foreach ($this->periods as $period) {
                        $gradeByStudentByPeriod = collect($subject['gradesByStudent'])
                            ->filter(function ($grade) use ($student, $period) {
                                return $student->id === $grade['student_id'] && $grade['period_id'] === $period->id;
                            })
                            ->first();

                        $totalSubjectAux = $existStudentLeveling == 0
                            ? ($gradeByStudentByPeriod['final'] ?? 0)
                            : $minimalGrade;
                        $totalSubject += ($totalSubjectAux * $subject['academic_wordload_porcentage']) * ($period->workload / 100);
                    }
                    $totalArea += $totalSubject;
                }
                $average += $totalArea;
            }

            $myAreaSpecialty = collect($this->areas)->filter(function ($filter) use ($student) {
                return $filter['id'] === $student->specialty;
            })->count();
            $areasGenerales = collect($this->areas)->filter(function ($filter) {
                return !$filter['isSpecialty'];
            })->count();

            $average = ($average / ($areasGenerales + $myAreaSpecialty)) ?? 0;
            $position[$student->id] = $average;
        }

        arsort($position);

        /* Estudiantes */
        foreach ($this->students as $key => $student) {
            $average = 0;
            $countSubjects = 0;
            $subjectsLosses = 0;
            $areasLosses = 0;
            $colGrade = 'B';

            $row = [
                ++$key,
                $student->getCompleteNames()
            ];

            /* notas */
            foreach ($this->areas as $area) {
                $sumArea = 0;
                $accumArea = 0;
                foreach ($area['subjects'] as $keySubject => $subject) {
                    $accumSubject = 0;
                    $totalSubject = 0;

                    $existStudentLeveling = !is_null($subject['teacher_subject_group'])
                        ? \App\Models\LeveledStudent::where('teacher_subject_group_id', $subject['teacher_subject_group'])->where('student_id', $student->id)->count()
                        : 0;

                    foreach ($this->periods as $period) {

                        $gradeByStudentByPeriod = collect($subject['gradesByStudent'])
                            ->filter(function ($grade) use ($student, $period) {
                                return $student->id === $grade['student_id'] && $grade['period_id'] === $period->id;
                            })
                            ->first();

                        $gradeSubject = $gradeByStudentByPeriod['final'] ?? null;

                        if ($this->period !== 'FINAL') {
                            // print grade
                            $colGrade++;
                            $row[] = $this->gradeController::numberFormat($this->ST, $gradeSubject) ?? '-';
                        }

                        // promedio area
                        $sumArea += $gradeByStudentByPeriod['final_workload'] ?? null;

                        // acumulado subject
                        $accumSubject += ($gradeByStudentByPeriod['final'] ?? 0) * ($period->workload / 100) ?? null;

                        // acumulado area
                        $accumAreaAux = $existStudentLeveling == 0
                            ? ($gradeByStudentByPeriod['final'] ?? 0)
                            : $minimalGrade;
                        $accumArea += ($accumAreaAux * $subject['academic_wordload_porcentage']) * ($period->workload / 100);

                        $totalSubject += $gradeByStudentByPeriod['final_workload'] ?? 0;

                        if ($this->period !== 'FINAL') {
                            if ($gradeSubject <= $this->ST->low_performance && !is_null($gradeSubject)) {
                                $this->lowGrades[] = $colGrade.$key+5;
                            }

                            if (@$gradeByStudentByPeriod['period_id'] === $this->period->id && $gradeSubject <= $this->ST->low_performance && !is_null($gradeSubject)) {
                                $subjectsLosses++;
                            }
                        }
                    }

                    $average += $totalSubject;
                    if (!$area['isSpecialty']) {
                        $countSubjects++;
                    }

                    /* nota faltante */
                    foreach ($periodsAfter as $periodAfter) {
                        $colGrade++;

                        $row[] = abs($minimalGrade - $accumSubject) * 100 / $missingPorcentage ;
                    }

                }

                /* total area */
                if (++$keySubject === count($area['subjects'])) {

                    // PROMEDIO
                    // $colGrade++;
                    // $row[] = $this->gradeController::numberFormat($this->ST, ($sumArea / count($this->periods))) ?? '-';

                    // ACUMULADO
                    $colGrade++;
                    $row[] = $this->gradeController::numberFormat($this->ST, ($accumArea)) ?? '-';
                    $row[] = $this->spaceCellVoid;

                    if ($accumArea <= $this->ST->low_performance && !is_null($accumArea) && $accumArea > 0) {
                        $areasLosses++;
                        $this->lowGrades[] = $colGrade.$key+5;
                    }

                    $colGrade++;
                }
            }

            if ($this->period !== 'FINAL') {
                /* ASIGNATURAS PERDIDAS */
                $row[] = $subjectsLosses ?: '0';
            }

            /* AREAS PERDIDAS */
            $row[] = $areasLosses ?: '0';

            if ($this->period !== 'FINAL') {
                /* ASIGNATURAS EVALUADAS */
                $myAreaSpecialty = collect($this->areas)->filter(function ($filter) use ($student) {
                    return $filter['id'] === $student->specialty;
                })->first() ?? ['subjects' => []];
                $totalSubjects = $countSubjects + count($myAreaSpecialty['subjects']) ?? 0;
                $average = ($average / $totalSubjects) ?? 0;
                $row[] = $totalSubjects;
                /* ASIGNATURAS EVALUADAS END */
            }

            /* PROMEDIO */
            $row[] = $this->gradeController::numberFormat($this->ST, $position[$student->id]) ?: '0';

            /* POSITION */
            $puesto = array_search($student->id, array_keys($position));
            $row[] = ++$puesto;

            $array[] = $row;
        }

        return $array;
    }

    public function styles(Worksheet $sheet)
    {
        $styles = [
            1 => [
                'font' => ['bold' => true, 'size' => 14],
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
            ],
            2 => [
                'font' => ['bold' => true, 'size' => 13],
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
            ],
            3 => [
                'font' => ['size' => 9],
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
            ],
            4 => [
                'font' => ['size' => 9],
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
            ],
            5 => [
                'font' => ['size' => 10],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'textRotation' => 90
                ]
            ],
            'C:FF' => [
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
            ],
            'A5:B5' => [
                'alignment' => ['textRotation' => 0]
            ]
        ];

        $start = 5;
        $end = 5 + count($this->students);

        foreach ($this->colAreas as $col) {
            $styles["{$col}{$start}:{$col}{$end}"] = [
                'font' => ['bold' => true,],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => [
                        'rgb' => 'DDDDDD'
                    ],
                ]
            ];
        }

        /* color a las areas de especialidad */
        foreach ($this->colAreasSpecialty as $col) {
            $styles["{$col}{$start}:{$col}{$end}"] = [
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => [
                        'rgb' => '1EA8E7'
                    ],
                ]
            ];
        }

        /* color a los periodos despues del actual */
        foreach ($this->colPeriodsAfter as $col) {
            $styles["{$col}{$start}:{$col}{$end}"] = [
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => [
                        'rgb' => 'FFE699'
                    ],
                ]
            ];
        }

        foreach ($this->lowGrades as $col) {
            $styles[$col] = [
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => [
                        'rgb' => 'f0adb4'
                    ],
                ],
                'font' => [
                    'color' => ['argb' => \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED],
                ]
            ];
        }

        return $styles;
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function (AfterSheet $event) {
                $event->sheet->mergeCells('A1:B1');
                $event->sheet->mergeCells('A2:B2');
                $event->sheet->mergeCells('A3:B3');
                $event->sheet->mergeCells('A4:B4');
            }
        ];
    }
}
