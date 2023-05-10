<?php

namespace App\Exports;

use App\Http\Controllers\GradeController;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class ConsolidateGeneralGradesGroup implements FromArray, WithColumnWidths, WithStyles, WithEvents
{
    private $group;
    private $ST;
    private $period;
    private $areas;
    private $students;

    private $gradeController;
    private $spaceCellVoid = "  ";
    private $colAreas = [];

    public function __construct($group, $ST, $period, $areas, $students)
    {
        $this->group = $group;
        $this->ST = $ST;
        $this->period = $period;
        $this->areas = $areas;
        $this->students = $students;

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
        for ($i=1; $i < 50; $i++) {
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

        foreach ($this->areas as $area) {
            foreach ($area['subjects'] as $key => $subject) {
                $colAreas++;
                $headers[] = $subject['resource_name'] . ' - ' . $subject['academic_workload'] . '%';
                if (++$key === count($area['subjects'])) {
                    $colAreas++;

                    $headers[] = "ÁREA: " . $area['name'];
                    $headers[] = $this->spaceCellVoid;
                    $this->colAreas[] = $colAreas;

                    $colAreas++;
                }
            }
        }
        $headers[] = 'ASIGNATURAS PERIDIDAS';
        $headers[] = 'ÁREAS PERDIDAS';

        $array = [
            [__('Group') . ': ' . $this->group->name],
            [__('Period') . ': ' . $this->period->name],
            [__('export.headquarters') . ': ' . $this->group->headquarters->name . ' | ' . __('export.study_time') . ': ' . $this->group->studyTime->name . ' | ' . __('export.study_year') . ': ' . $this->group->studyYear->name],
            [null],
            $headers
        ];

        /* Estudiantes */
        foreach ($this->students as $key => $student) {
            $subjectsLosses = 0;
            $areasLosses = 0;

            $row = [
                ++$key,
                $student->getCompleteNames()
            ];

            /* notas */
            foreach ($this->areas as $area) {
                $sumArea = 0;
                foreach ($area['subjects'] as $keySubject => $subject) {
                    $gradeByStudentByPeriod = collect($subject['gradesByStudent'])
                        ->filter(function ($grade) use ($student) {
                            return $student->id === $grade['student_id'] && $grade['period_id'] === $this->period->id;
                        })
                        ->first();

                    $gradeSubject = $gradeByStudentByPeriod['final'] ?? null;
                    $row[] = $this->gradeController::numberFormat($this->ST, $gradeSubject) ?? '-';
                    $sumArea += $gradeByStudentByPeriod['final_workload'] ?? null;

                    if ($gradeSubject <= $this->ST->low_performance && !is_null($gradeSubject)) {
                        $subjectsLosses++;
                    }


                    /* total area */
                    if (++$keySubject === count($area['subjects'])) {
                        $row[] = $this->gradeController::numberFormat($this->ST, $sumArea) ?? '-';
                        $row[] = $this->spaceCellVoid;

                        if ($sumArea <= $this->ST->low_performance && !is_null($sumArea) && $sumArea > 0) {
                            $areasLosses++;
                        }
                    }
                }
            }
            $row[] = $subjectsLosses ?: '0';
            $row[] = $areasLosses ?: '0';

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
            }
        ];
    }
}
