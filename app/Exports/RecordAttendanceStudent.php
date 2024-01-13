<?php

namespace App\Exports;

use App\Http\Controllers\SchoolYearController;
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
        $Y = SchoolYearController::current_year();

        $array = [
            [$this->student->getCompleteNames()],
            ['Grupo', $this->student->group->name],
            ['#', 'Inasistencia', 'Fecha', 'Asignatura']
        ];

        $attendaces = AttendanceStudent::where('student_id', $this->student->id)
            ->whereIn('attend', ['N', 'J', 'L'])
            ->withWhereHas('attendance',
                fn($attendQuery) => $attendQuery->withWhereHas('teacherSubjectGroup',
                    fn($tsgQuery) => $tsgQuery->whereHas('group',
                        fn($gQuery) => $gQuery->where('school_year_id', $Y->id)
                    )->with('subject')
                )
            )
            ->get();

        foreach ($attendaces as $i => $attend) {

            array_push($array, [
                ++$i,
                $attend->attend->getLabelText(),
                $attend->attendance->date,
                $attend->attendance->teacherSubjectGroup->subject->resourceSubject->name
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
