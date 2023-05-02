<?php

namespace App\Imports;

use App\Models\Descriptor;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DescriptorImport implements ToCollection, WithHeadingRow
{
    private $subjectId;
    private $studyYearId;

    public function __construct($subjectId, $studyYearId)
    {
        $this->subjectId = $subjectId;
        $this->studyYearId = $studyYearId;
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

        // $Y = SchoolYearController::current_year();

        foreach ($rows as $row) {

            /*
             * Validating that the columns exist.
             */
            if(!isset( $row['period'] )) {
                throw ValidationException::withMessages(['data' => 'La columna (period) no existe']);
            } else
            if(!isset( $row['content'] )) {
                throw ValidationException::withMessages(['data' => 'La columna (content) no existe']);
            }


            /*
             * Validating that the email is not empty.
             */
            if (empty(trim($row['period']))) {
                throw ValidationException::withMessages(['data' => 'hay un periodo vacio']);
            } else
            if (! in_array($row['period'], [1,2,3,4,5,6])) {
                throw ValidationException::withMessages(['data' => 'hay un periodo fuera del rango (1 ... 6)']);
            } else
            if (empty(trim($row['content']))) {
                throw ValidationException::withMessages(['data' => 'hay un contenido vacio']);
            }

            /* CAST */
            $inclusive = FALSE;
            if (! empty(trim($row['inclusive']))) {
                $inclusive = in_array(strtolower($row['inclusive']), ['1', 1, 'yes', 'si']) ?? TRUE;
            }

            Descriptor::create([
                'resource_study_year_id' => $this->studyYearId,
                'resource_subject_id' => $this->subjectId,
                'period' => (int)$row['period'],
                'inclusive' => $inclusive,
                'content' => trim($row['content'])
            ]);

        }
    }
}
