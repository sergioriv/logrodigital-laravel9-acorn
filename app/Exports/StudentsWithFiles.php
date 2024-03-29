<?php

namespace App\Exports;

use App\Http\Controllers\SchoolYearController;
use App\Models\Student;
use App\Models\StudentFileType;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class StudentsWithFiles implements FromArray, WithHeadings, ShouldAutoSize, WithStyles, WithEvents
{

    private $docCompletes = [1 => ['font' => ['bold' => true]]];
    private $studentFiles;
    private $students;

    public function __construct($students)
    {
        $this->students = $students;
        $this->studentFiles = StudentFileType::where('inclusive', 0)->get();
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function array(): array
    {

        $array = [];

        $docRequired = StudentFileType::where('required', 1)->count();

        $i = 2;
        foreach ($this->students as $student) {
            $row = [
                $student->headquarters->name,
                $student->studyTime->name,
                $student->studyYear->name,
                $student->first_last_name,
                $student->second_last_name,
                $student->first_name,
                $student->second_name,
                $student->telephone,
                $student->institutional_email,
                $student->document_type_code,
                $student->document
            ];

            $docComplete = 0;
            foreach ($this->studentFiles as $SF) {
                $file = $student->files->filter(function ($item) use ($SF) {
                    return $item->student_file_type_id == $SF->id;
                });

                if ($file->isEmpty())
                    array_push($row, 'NO');
                else {
                    $docComplete = $SF->required == 1 ?? $docComplete + 1;
                    array_push($row, 'SI');
                }
            }

            if ($docRequired == $docComplete) {
                array_push($row, 'DOCUMENTOS REQUERIDOS COMPLETOS');
                $this->docCompletes[$i] = [
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'rgb' => 'B6FFA4'
                        ],
                    ]
                ];
            }

            array_push($array, $row);
            $i++;
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
            "Teléfono",
            "Correo electrónico",
            "Tipo doc.",
            "Documento",
        ];

        foreach ($this->studentFiles as $SF) {
            array_push($titles, $SF->required ? $SF->name .' *' : $SF->name);
        }

        return $titles;
    }

    public function styles(Worksheet $sheet)
    {
        return $this->docCompletes;
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

                $event->sheet->getDelegate()->getStyle('J:Z')
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                $event->sheet->getDelegate()->getStyle('1')
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            },
        ];
    }
}
