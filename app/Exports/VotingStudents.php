<?php

namespace App\Exports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class VotingStudents implements FromArray, ShouldAutoSize, WithStyles, WithEvents
{
    private $voting;
    private $students;
    private $stylesVoting = [
        1 => [
            'font' => ['bold' => true, 'size' => 14],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
        ],
        2 => [
            'font' => ['bold' => true, 'size' => 12],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
        ],
    ];

    public function __construct($voting, $students)
    {
        $this->voting = $voting;
        $this->students = $students;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function array(): array
    {
        $array = [
            [$this->voting->title],
            ['#', 'Nombre completo', 'documento', 'grupo']
        ];

        foreach ($this->students as $i => $student) {
            array_push($array, [++$i, $student->getCompleteNames(), $student->document, $student->group->name]);

            if ( $student->voted_count > 0 ) {
                $this->stylesVoting[$i+2] = [
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'rgb' => 'B6FFA4'
                        ],
                    ]
                ];
            }
        }

        return $array;
    }

    public function styles(Worksheet $sheet)
    {
        return $this->stylesVoting;
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

}
