<?php

namespace App\Exports;

use App\Models\Teacher;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TeachersExport implements FromArray, ShouldAutoSize, WithStyles
{
    public function array(): array
    {
        $array = [
            ["Nombres", "Apellidos", "Telefono", "Celular", "Correo institucional", "Ciudad de expedición", "No. documento", "Fecha nacimiento", "Ciudad residencia", "Dirección", "Estado civil", "Fecha de ingreso"]
        ];

        $teachers = Teacher::select(
            "names",
            "last_names",
            "telephone",
            "cellphone",
            "institutional_email",
            "expedition_city",
            "document",
            "birthdate",
            "residence_city",
            "address",
            "marital_status",
            "date_entry")
            ->with('expeditionCity', 'birthCity', 'residenceCity')->get();

        foreach ($teachers as $teacher) {

            $array[] = [
                $teacher->names,
                $teacher->last_names,
                $teacher->telephone,
                $teacher->cellphone,
                $teacher->institutional_email,
                $teacher->expeditionCity?->name,
                $teacher->document,
                $teacher->birthdate,
                $teacher->residenceCity?->name,
                $teacher->address,
                is_null($teacher->marital_status) ? null : __($teacher->marital_status),
                $teacher->date_entry
            ];

        }

        return $array;
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
