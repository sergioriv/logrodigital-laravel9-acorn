<?php

namespace App\Exports;

use App\Http\Controllers\SchoolYearController;
use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;


class StudentsForHeadquartersExport implements FromArray, WithHeadings, ShouldAutoSize, WithStyles/* , WithEvents */
{
    private $students;

    public function __construct($students)
    {
        $this->students = $students;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function array(): array
    {
        $array = [];

        foreach ($this->students as $student) {

            $row = [];

            array_push($row,
                $student->headquarters->name,
                $student->studyTime->name,
                $student->studyYear->name,
                $student->first_last_name,
                $student->second_last_name,
                $student->first_name,
                $student->second_name,
                $student->group->name
            );

            array_push($array, $row);
        }

        return $array;
    }

    public function headings(): array
    {
        $titles = [
            "Sede",
            "Jornada",
            "Año de estudio",
            "Primer apellido",
            "Segundo apellido",
            "Primer nombre",
            "Segundo nombre",
            "Grupo"
        ];

        return $titles;
    }

    public function styles(Worksheet $sheet)
    {
        return [1 => ['font' => ['bold' => true]]];
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    /* public function registerEvents(): array
    {
        return [];
    } */
}
