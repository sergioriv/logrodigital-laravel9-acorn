<?php

namespace App\Exports;

use App\Http\Controllers\SchoolController;
use App\Models\Group;
use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class GroupStudentsAttendanceControl implements FromArray, WithColumnWidths, WithEvents, WithStyles
{
    use RegistersEventListeners;

    private static $group;
    private static $title;
    private static $subTitle;
    private static $students;

    public function __construct(Group $group)
    {
        $school = SchoolController::myschool();

        static::$group = $group;

        static::$title = $school->name();
        static::$subTitle = "DANE {$school->dane()} - NIT {$school->nit()}";
    }

    public function array(): array
    {
        $content = [
            [static::$title],
            [static::$subTitle],
            [""],
            ["CONTROL DE ASISTENCIA, RETARDOS Y PRESENTACIÓN PERSONAL"],
            ["GRUPO ". static::$group->name ." | MES:"],
            [
                "#", "APELLIDOS Y NOMBRES",
                "1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12", "13", "14", "15", "16", "17", "18", "19", "20", "21", "22", "23", "24", "25", "26", "27", "28", "29", "30", "31",
                "FALLAS", "INCAPACIDADES", "PERMISOS", "EVACIONES", "TOTAL"
            ]
        ];

        $studentsGroup = Student::singleData()->whereHas('groupYear', fn($gr) => $gr->where('group_id', static::$group->id))->get();
        foreach ($studentsGroup as $key => $student) {
            $content[] = [
                $key+1,
                $student->getCompleteNames(),
            ];
        }

        return $content;
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
            5 => [
                'font' => ['bold' => true, 'size' => 12],
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
            ],
            6 => [
                'font' => ['bold' => true, 'size' => 11],
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 50,
            'C' => 4,
            'D' => 4,
            'E' => 4,
            'F' => 4,
            'G' => 4,
            'H' => 4,
            'I' => 4,
            'J' => 4,
            'K' => 4,
            'L' => 4,
            'M' => 4,
            'N' => 4,
            'O' => 4,
            'P' => 4,
            'Q' => 4,
            'R' => 4,
            'S' => 4,
            'T' => 4,
            'U' => 4,
            'V' => 4,
            'W' => 4,
            'X' => 4,
            'Y' => 4,
            'Z' => 4,
            'AA' => 4,
            'AB' => 4,
            'AC' => 4,
            'AD' => 4,
            'AE' => 4,
            'AF' => 4,
            'AG' => 4,
            'AH' => 12,
            'AI' => 12,
            'AJ' => 12,
            'AK' => 12,
            'AL' => 12,
        ];
    }

    public static function afterSheet(AfterSheet $event)
    {
        try {
            $workSheet = $event
                ->sheet
                ->getDelegate()
                ->setMergeCells([
                    'A1:AL1', //nombre institucion
                    'A2:AL2', //info institucion
                    'A3:AL3', //en blanco
                    'A4:AL4', //titulo
                    'A5:AL5', //subtitulo
                ]);

            $workSheet->getStyle('A1:AL' . static::$group->groupStudents->count() + 6)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ]
                ]);
        } catch (\Exception $exception) {
            throw $exception;
        }
    }
}
