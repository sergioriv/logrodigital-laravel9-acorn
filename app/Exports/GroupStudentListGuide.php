<?php

namespace App\Exports;

use App\Http\Controllers\SchoolController;
use App\Http\Controllers\SchoolYearController;
use App\Models\Student;
use App\Models\TeacherSubjectGroup;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class GroupStudentListGuide implements WithColumnWidths, WithEvents
{
    use RegistersEventListeners;

    private static $GROUP;
    private static $TSG;
    private static $title;
    private static $subTitle;
    private static $students;

    public function __construct(TeacherSubjectGroup $tsg)
    {
        $school = SchoolController::myschool();

        static::$GROUP = $tsg->group;
        static::$TSG = $tsg;

        static::$title = $school->name() .' - SEDE '. $tsg->group->headquarters->name .' - JORNADA '. $tsg->group->studyTime->name;
        static::$subTitle = 'PLANILLA DE NOTAS ' . SchoolYearController::current_year()->name;

        static::$students = Student::singleData()->whereHas('groupYear', fn($gr) => $gr->where('group_id', $tsg->group->id))
        ->get();
    }

    public function columnWidths(): array
    {
        // 10 => 70 puntos
        return [
            'A' => 5,
            'B' => 50,
            'C' => 7,
            'D' => 7,
            'E' => 7,
            'F' => 7,
            'G' => 7,
            'H' => 7,
            'I' => 7,
            'J' => 7,
            'K' => 7,
            'L' => 7,
            'M' => 7,
            'N' => 7,
            'O' => 7,
            'P' => 7,
            'Q' => 7,
            'R' => 7,
            'S' => 7,
            'T' => 7,
            'U' => 7,
            'V' => 7,
            'W' => 7,
            'X' => 7,
        ];
    }

    public static function afterSheet(AfterSheet $event)
    {
        try {
            $workSheet = $event
                ->sheet
                ->getDelegate()
                ->setMergeCells([
                    'A1:V1', //titulo
                    'A2:V2', //subtitulo
                    'W1:X3', //periodo
                    'A3:B5', //grupo
                    'C3:V3', //docente
                    'C4:I4', //area
                    'J4:Q4', //asignatura
                    'R4:R6', //niv
                    'S4:U4', //notas finales
                    'V4:V6', //fallas
                    'W4:X5', //vacio
                    'C5:G6', //conceptual
                    'H5:L6', //procedimental
                    'M5:Q6', //actitudinal
                ])
                ->freezePane('A7');

            $headers = $workSheet->getStyle('A1:X2');

            $headers
                ->getAlignment()
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
                ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $headers
                ->getFont()
                ->setBold(true);

            $workSheet->getStyle('A')
                ->getAlignment()
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $workSheet->getStyle('A3')
                ->getAlignment()
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
                ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $workSheet->getStyle('A3')
                ->getFont()
                ->setBold(true);
            $workSheet->getStyle('S4')
                ->getAlignment()
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $workSheet->getStyle('B6')
                ->getAlignment()
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $workSheet->getStyle('B6')
                ->getFont()
                ->setBold(true);
            $workSheet->getStyle('C5')
                ->getAlignment()
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
                ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $workSheet->getStyle('C5')
                ->getFont()
                ->setBold(true);
            $workSheet->getStyle('H5')
                ->getAlignment()
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
                ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $workSheet->getStyle('H5')
                ->getFont()
                ->setBold(true);
            $workSheet->getStyle('M5')
                ->getAlignment()
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
                ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $workSheet->getStyle('M5')
                ->getFont()
                ->setBold(true);
            $workSheet->getStyle('R4:X6')
                ->getAlignment()
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
                ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $workSheet->getStyle('A1:B' . static::$GROUP->groupStudents->count() + 6)
                ->getAlignment()
                ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $workSheet->getStyle('A1:X' . static::$GROUP->groupStudents->count() + 6)->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => '000000'],
                    ],
                ]
            ]);


            /* VALORES */
            $workSheet->setCellValue('A1', static::$title);
            $workSheet->setCellValue('A2', static::$subTitle);
            $workSheet->setCellValue('W1', 'PERIODO');
            $workSheet->setCellValue('A3', 'GRUPO: ' . static::$GROUP->name);
            $workSheet->setCellValue('C3', 'DOCENTE: ' . static::$TSG?->teacher?->getFullName());
            $workSheet->setCellValue('C4', 'ÁREA: ' . static::$TSG?->subject->resourceArea->name);
            $workSheet->setCellValue('J4', 'ASIGNATURA: ' . static::$TSG?->subject->resourceSubject->name);
            $workSheet->setCellValue('R4', 'NIV');
            $workSheet->setCellValue('S4', 'NOTAS FINALES');
            $workSheet->setCellValue('V4', 'FALLAS');
            $workSheet->setCellValue('C5', 'CONCEPTUAL');
            $workSheet->setCellValue('H5', 'PROCEDIMENTAL');
            $workSheet->setCellValue('M5', 'ACTITUDINAL');
            $workSheet->setCellValue('S5', 'CON');
            $workSheet->setCellValue('T5', 'PRO');
            $workSheet->setCellValue('U5', 'ACT');
            $workSheet->setCellValue('A6', '#');
            $workSheet->setCellValue('B6', 'APELLIDOS Y NOMBRES');
            $workSheet->setCellValue('S6', static::$GROUP->studyTimeSelectAll->conceptual . '%');
            $workSheet->setCellValue('T6', static::$GROUP->studyTimeSelectAll->procedural . '%');
            $workSheet->setCellValue('U6', static::$GROUP->studyTimeSelectAll->attitudinal . '%');
            $workSheet->setCellValue('W6', 'DEFINITIVA');
            $workSheet->setCellValue('X6', 'DEF');

            foreach (static::$students as $key => $student) {
                $workSheet->setCellValue('A' . $key+7, $key+1);
                $workSheet->setCellValue('B' . $key+7, $student->getCompleteNames());
                $event->sheet->getDelegate()->getRowDimension($key+7)->setRowHeight(20);
            }

        } catch (\Exception $exception) {
            throw $exception;
        }
    }

}
