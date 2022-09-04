<?php

namespace App\Exports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StudentsInstructuveExport implements WithHeadings, WithColumnWidths, WithStyles
{
    public function headings(): array
    {
        return [
            "first_name",
            "second_name",
            'first_last_name',
            'second_last_name',
            'document_type',
            'document',
            'institutional_email',
            'telephone',
            'expedition_city',
            'number_siblings',
            'birth_city',
            'birthdate',
            'gender',
            'rh',

            'zone',
            'residence_city',
            'address',
            'neighborhood',
            'social_stratum',
            'dwelling_type',
            'electrical_energy',
            'natural_gas',
            'sewage_system',
            'aqueduct',
            'internet',
            'lives_with_father',
            'lives_with_mother',
            'lives_with_siblings',
            'lives_with_other_relatives',

            'headquarters',
            'study_time',
            'study_year' //AE
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20,
            'B' => 20,
            'C' => 20,
            'D' => 20,
            'E' => 20,
            'F' => 20,
            'G' => 20,
            'H' => 20,
            'I' => 20,
            'J' => 20,
            'K' => 20,
            'L' => 20,
            'M' => 20,
            'N' => 20,
            'O' => 20,
            'P' => 20,
            'Q' => 20,
            'R' => 20,
            'S' => 20,
            'T' => 20,
            'U' => 20,
            'V' => 20,
            'W' => 20,
            'X' => 20,
            'Y' => 20,
            'Z' => 20,
            'AA' => 20,
            'AB' => 20,
            'AC' => 20,
            'AD' => 20,
            'AE' => 20,
            'AF' => 20
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true]],

            // Styling an entire column.
            // 'C'  => ['font' => ['size' => 16]],
        ];
    }
}
