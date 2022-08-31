<?php

namespace App\Imports;

use App\Http\Controllers\ProviderUser;
use App\Http\Controllers\SchoolYearController;
use App\Models\City;
use App\Models\Headquarters;
use App\Models\Student;
use App\Models\StudyTime;
use App\Models\StudyYear;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

use Illuminate\Support\Str;

class StudentsImport implements ToCollection, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function collection(Collection $rows)
    {
        if(count( $rows ) === 0) {
            throw ValidationException::withMessages(['data' => 'El archivo no contiene estudiantes']);
        }

        $Y = SchoolYearController::current_year();

        foreach ($rows as $row) {

            /*
             * Validating that the columns exist.
             */
            if(!isset( $row['first_name'] )) {
                throw ValidationException::withMessages(['data' => 'La columna (first_name) no existe']);
            } else
            if(!isset( $row['father_last_name'] )) {
                throw ValidationException::withMessages(['data' => 'La columna (father_last_name) no existe']);
            } else
            if(!isset( $row['document_type'] )) {
                throw ValidationException::withMessages(['data' => 'La columna (document_type) no existe']);
            } else
            if(!isset( $row['document'] )) {
                throw ValidationException::withMessages(['data' => 'La columna (document) no existe']);
            } else
            if(!isset( $row['institutional_email'] )) {
                throw ValidationException::withMessages(['data' => 'La columna (institutional_email) no existe']);
            } else
            if(!isset( $row['headquarters'] )) {
                throw ValidationException::withMessages(['data' => 'La columna (headquarters) no existe']);
            } else
            if(!isset( $row['study_time'] )) {
                throw ValidationException::withMessages(['data' => 'La columna (study_time) no existe']);
            } else
            if(!isset( $row['study_year'] )) {
                throw ValidationException::withMessages(['data' => 'La columna (study_year) no existe']);
            }


            /*
             * Validating that the email is not empty.
             */
            if (empty(trim($row['first_name']))) {
                throw ValidationException::withMessages(['data' => 'hay un (first_name) vacio']);
            } else
            if (empty(trim($row['father_last_name']))) {
                throw ValidationException::withMessages(['data' => 'hay un (father_last_name) vacio']);
            } else
            if (empty(trim($row['document_type']))) {
                throw ValidationException::withMessages(['data' => 'hay un (document_type) vacio']);
            } else
            if (empty(trim($row['document']))) {
                throw ValidationException::withMessages(['data' => 'hay un (document) vacio']);
            } else
            if (empty(trim($row['institutional_email']))) {
                throw ValidationException::withMessages(['data' => 'hay un (institutional_email) vacio']);
            } else
            if (empty(trim($row['headquarters']))) {
                throw ValidationException::withMessages(['data' => 'hay un (headquarters) vacio']);
            } else
            if (empty(trim($row['study_time']))) {
                throw ValidationException::withMessages(['data' => 'hay un (study_time) vacio']);
            } else
            if (empty(trim($row['study_year']))) {
                throw ValidationException::withMessages(['data' => 'hay un (study_year) vacio']);
            }


            /* Formating data */
            if($row['expedition_city']) {
                $cityEx = Str::lower($row['expedition_city']);
                $row['expedition_city'] = City::where('name', $cityEx)->first()->id ?? null;
            }
            if($row['birthdate']) {
                $row['birthdate'] = Date::excelToDateTimeObject($row['birthdate'])->format('Y-m-d');
            }


            /*
             * Validating that the email is unique.
             */
            $row['institutional_email'] = Str::lower($row['institutional_email']);
            $validEmail = User::where('email', $row['institutional_email'])->first();

            if ($validEmail) {
                throw ValidationException::withMessages(['data' => 'El correo (' . $row['institutional_email'] . ') ya se encuentra registrado!']);
            }

            /*
             * Validating that the document is unique.
             */
            $document = Student::where('document', $row['document'])->first();

            if ($document) {
                throw ValidationException::withMessages(['data' => 'El documento (' . $row['document'] . ') ya se encuentra registrado!']);
            }

            $row['headquarters']    = Headquarters::where('name',$row['headquarters'])->first()->id;
            $row['study_time']      = StudyTime::where('name',$row['study_time'])->first()->id;
            $row['study_year']      = StudyYear::where('name',$row['study_year'])->first()->id;


            /*
             * Creating a new user and a new student.
             */
            $provider = ProviderUser::provider_validate($row['institutional_email']);

            $newUser = User::create([
                'provider' => $provider,
                'name'     => $row['first_name'] . ' ' . $row['father_last_name'],
                'email'    => $row['institutional_email'],
            ])->assignRole(7);
            $newUser->forceFill(['email_verified_at' => now()])->save();

            Student::create([
                'id'                    => $newUser->id,
                'first_name'            => $row['first_name'],
                'second_name'           => $row['second_name'],
                'father_last_name'      => $row['father_last_name'],
                'mother_last_name'      => $row['mother_last_name'],
                'document_type_code'    => $row['document_type'],
                'document'              => $row['document'],
                'institutional_email'   => $row['institutional_email'],
                'telephone'             => $row['telephone'],
                'expedition_city_id'    => $row['expedition_city'],
                'number_siblings'       => $row['number_siblings'],
                'birth_city_id'         => $row['birth_city'],
                'birthdate'             => $row['birthdate'],
                'gender_id'             => $row['gender'],
                'rh_id'                 => $row['rh'],

                'zone'                  => $row['zone'],
                'residence_city_id'     => $row['residence_city'],
                'address'               => $row['address'],
                'neighborhood'          => $row['neighborhood'],
                'social_stratum'        => $row['social_stratum'],
                'dwelling_type_id'      => $row['dwelling_type'],
                'electrical_energy'     => $row['electrical_energy'],
                'natural_gas'           => $row['natural_gas'],
                'sewage_system'         => $row['sewage_system'],
                'aqueduct'              => $row['aqueduct'],
                'internet'              => $row['internet'],
                'lives_with_father'     => $row['lives_with_father'],
                'lives_with_mother'     => $row['lives_with_mother'],
                'lives_with_siblings'   => $row['lives_with_siblings'],
                'lives_with_other_relatives' => $row['lives_with_other_relatives'],

                'school_year_create'    => $Y->id,
                'headquarters_id'       => $row['headquarters'],
                'study_time_id'         => $row['study_time'],
                'study_year_id'         => $row['study_year'],

                'group_id' => NULL,
                'enrolled_date' => NULL,
                'enrolled' => NULL,
                'data_treatment' => TRUE
            ]);
        }
    }
}
