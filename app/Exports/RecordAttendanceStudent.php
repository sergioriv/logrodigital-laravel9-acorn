<?php

namespace App\Exports;

use App\Models\AttendanceStudent;
use App\Models\Student;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class RecordAttendanceStudent implements FromArray, ShouldAutoSize, WithStyles, WithEvents
{
    private $student;

    public function __construct($student)
    {
        $this->student = $student;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function array(): array
    {
        $array = [
            [$this->student->getCompleteNames()],
            ['Grupo', $this->student->group->name],
            ['#', 'Inasistencia', 'Fecha', 'Asignatura']
        ];

        $attendaces = AttendanceStudent::where('student_id', $this->student->id)
            ->whereIn('attend', ['N', 'J', 'L'])
            ->with(['attendance' => fn($at) => $at->with([
                'teacherSubjectGroup' =>
                fn ($TSG) => $TSG->with('subject')
            ])])
            ->get();

        foreach ($attendaces as $i => $attend) {

            array_push($array, [
                ++$i,
                $this->match($attend->attend),
                $attend->attendance->date,
                $attend->attendance->teacherSubjectGroup->subject->resourceSubject->name
            ]);
        }

        return $array;
    }

    private function match($attend)
    {
        return match ($attend) {
            'N' => 'No justificada',
            'J' => 'Justificada',
            'L' => 'Llegada tarde',
        };
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 14],
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
            ],
            3 => [
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
