<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class TeachersWithNoAttendance implements FromArray, ShouldAutoSize, WithStyles, WithEvents
{
    private $title;
    private $teachers;

    public function __construct($title, $teachers)
    {
        $this->title = $title;
        $this->teachers = $teachers;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function array(): array
    {
        $array = [
            [$this->title],
            ['#', 'Apellidos', 'Nombres', 'Correo electrónico']
        ];


        foreach ($this->teachers as $i => $teacher) {

            array_push($array, [
                ++$i,
                $teacher->last_names,
                $teacher->names,
                $teacher->institutional_email
            ]);
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
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
            ]
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function (AfterSheet $event) {
                $event->sheet->mergeCells('A1:D1');
            }
        ];
    }

}
