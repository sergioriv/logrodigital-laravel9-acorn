<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class TeachersCountData implements FromArray, ShouldAutoSize, WithStyles, WithEvents
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
            ['#', 'Apellidos', 'Nombres', 'Campos diligenciados', 'Cant. Escalafón', 'Cant. Títulos obtenido', 'Cant. Historial laboral']
        ];


        foreach ($this->teachers as $i => $teacher) {

            array_push($array, [
                ++$i,
                $teacher->last_names,
                $teacher->names,
                "{$this->countFields($teacher)} / 12",
                $teacher->hierarchies_count,
                $teacher->degrees_count,
                $teacher->employments_count,
            ]);
        }

        return $array;
    }

    private function countFields($teacher)
    {
        $fields = 0;

        if ($teacher->names) $fields++;
        if ($teacher->last_names) $fields++;
        if ($teacher->institutional_email) $fields++;
        if ($teacher->document) $fields++;
        if ($teacher->expedition_city) $fields++;
        if ($teacher->birth_city) $fields++;
        if ($teacher->birthdate) $fields++;
        if ($teacher->residence_city) $fields++;
        if ($teacher->address) $fields++;
        if ($teacher->telephone) $fields++;
        if ($teacher->cellphone) $fields++;
        if ($teacher->marital_status) $fields++;

        return $fields;
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
            ],
            'D' => [
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
            ]
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function (AfterSheet $event) {
                $event->sheet->mergeCells('A1:G1');
            }
        ];
    }

}
