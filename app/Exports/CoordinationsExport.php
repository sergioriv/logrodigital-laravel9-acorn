<?php

namespace App\Exports;

use App\Models\Coordination;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CoordinationsExport implements FromArray, ShouldAutoSize, WithStyles
{
    public function array(): array
    {
        $array = [
            ["Nombres", "Apellidos", "Telefono", "Celular", "Correo institucional", "Ciudad de expedición", "No. documento", "Fecha nacimiento", "Ciudad residencia", "Dirección", "Estado civil", "Fecha de ingreso"]
        ];

        $coordinations = Coordination::select(
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

        foreach ($coordinations as $coordination) {

            $array[] = [
                $coordination->names,
                $coordination->last_names,
                $coordination->telephone,
                $coordination->cellphone,
                $coordination->institutional_email,
                $coordination->expeditionCity?->name,
                $coordination->document,
                $coordination->birthdate,
                $coordination->residenceCity?->name,
                $coordination->address,
                is_null($coordination->marital_status) ? null : __($coordination->marital_status),
                $coordination->date_entry
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
