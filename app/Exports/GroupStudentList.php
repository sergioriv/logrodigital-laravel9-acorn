<?php

namespace App\Exports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class GroupStudentList implements FromArray, WithColumnWidths, WithStyles, WithEvents
{
    private $group;

    public function __construct($group)
    {
        $this->group = $group;
    }

    public function columnWidths(): array
    {
        // 10 => 70 puntos
        return [
            'A' => 5,
            'B' => 100,
        ];
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function array(): array
    {
        $array = [
            [__('Group') .': '. $this->group->name],
            [__('export.headquarters') .': '. $this->group->headquarters->name .' | '. __('export.study_time') .': '. $this->group->studyTime->name .' | '. __('export.study_year') .': '. $this->group->studyYear->name ],
            [null],
            ['#', __('Full name')]
        ];

        $studentsGroup = Student::singleData()->whereHas('groupYear', fn($gr) => $gr->where('group_id', $this->group->id))->get();
        foreach ($studentsGroup as $i => $student) {
            array_push($array, [++$i, $student->getCompleteNames()]);
        }

        return $array;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 14],
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
            ],
            2 => [
                'font' => ['size' => 9],
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
            ],
            4 => [
                'font' => ['bold' => true, 'size' => 12],
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
            ],
        ];
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
            }
        ];
    }
}
