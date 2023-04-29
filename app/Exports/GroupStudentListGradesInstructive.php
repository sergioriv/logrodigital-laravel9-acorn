<?php

namespace App\Exports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class GroupStudentListGradesInstructive implements FromArray, ShouldAutoSize, WithStyles, WithColumnFormatting
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
            ['codigo', 'nombres', 'nota']
        ];

        $studentsGroup = Student::select('id', 'code', 'first_name', 'second_name', 'first_last_name', 'second_last_name')->whereHas('groupYear', fn($gr) => $gr->where('group_id', $this->group->id))->get();
        foreach ($studentsGroup as $student) {
            array_push($array, [$student->code, $student->getCompleteNames()]);
        }

        return $array;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 11],
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
            ]
        ];
    }


    public function columnFormats(): array
    {
        return [
            'C' => \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER,
        ];
    }


}
