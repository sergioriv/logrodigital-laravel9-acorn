<?php

namespace App\Imports;

use App\Http\Controllers\GradeController;
use App\Models\Descriptor;
use App\Models\Grade;
use App\Models\Student;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class GroupGradesImport implements ToCollection, WithHeadingRow
{
    private $tsg;
    private $ST;
    private $periodId;

    public function __construct($tsg, $ST, $periodId)
    {
        $this->tsg = $tsg;
        $this->ST = $ST;
        $this->periodId = $periodId;
    }
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function collection(Collection $rows)
    {
        if(count( $rows ) === 0) {
            throw ValidationException::withMessages(['data' => 'El archivo no contiene información']);
        }

        foreach ($rows as $row) {

            $code = null;
            $grade = null;

            /*
             * Validating that the columns exist.
             */
            if ( ! isset($row['codigo']) ) throw ValidationException::withMessages(['data' => 'La columna (codigo) no existe']);

            $code = trim($row['codigo']);
            $grade = trim($row['nota']);


            /*
             * Validating that the email is not empty.
             */
            if ( empty( $code ) || is_null( $code ) ) throw ValidationException::withMessages(['data' => 'hay un codigo vacio']);


            $student = Student::select('id')->whereCode($code)
                ->when(!$this->tsg->group->specialty, function ($whenSpecialty) {
                    $whenSpecialty->where('group_id', $this->tsg->group_id);
                }, function ($whenNotSpecialty) {
                    $whenNotSpecialty->where('group_specialty_id', $this->tsg->group_id);
                })
                ->first();

            if ( is_null( $student ) ) throw ValidationException::withMessages(['data' => 'el estudiante con codigo ' . $code . ' no pertenece a este grupo']);

            if ( ! empty($grade) ) {

                /* validate max and min */
                $gradeformating = GradeController::validateGradeWithStudyTime($this->ST, $grade);

                if ( ! is_null($gradeformating) ) {
                    Grade::updateOrCreate([
                            'teacher_subject_group_id' => $this->tsg->id,
                            'period_id' => $this->periodId,
                            'student_id' => $student->id
                        ],
                        [
                            'final' => $gradeformating
                        ]
                    );
                }
            }
        }
    }
}
