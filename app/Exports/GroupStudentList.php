<?php

namespace App\Exports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class GroupStudentList implements FromArray, ShouldAutoSize, WithStyles, WithEvents, WithColumnFormatting
{
    private $group;

    public function __construct($group)
    {
        $this->group = $group;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function array(): array
    {
        $array = [
            [__('Group') .': '. $this->group->name],
            [null],
            [__('Full name'), __('conceptual'), __('procedural'), __('attitudinal')]
        ];

        $studentsGroup = Student::singleData()->whereHas('groupYear', fn($gr) => $gr->where('group_id', $this->group->id))->get();
        foreach ($studentsGroup as $student) {
            array_push($array, [$student->getCompleteNames()]);
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
            3 => [
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
                $event->sheet->mergeCells('A1:D1');
            }
        ];
    }

    public function columnFormats(): array
    {
        return [
            'B' => \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER,
            'C' => \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER,
            'D' => \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER,
        ];
    }


}
