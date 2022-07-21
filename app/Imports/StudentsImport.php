<?php

namespace App\Imports;

use App\Http\Controllers\ProviderUser;
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

        foreach ($rows as $row) {

            /*
             * Validating that the columns exist.
             */
            if(!isset( $row['first_name'] )) {
                throw ValidationException::withMessages(['data' => 'La columna (first_name) no existe']);
            } else
            /* if(!isset( $row['second_name'] )) {
                throw ValidationException::withMessages(['data' => 'La columna (second_name) no existe']);
            } else */
            if(!isset( $row['father_last_name'] )) {
                throw ValidationException::withMessages(['data' => 'La columna (father_last_name) no existe']);
            } else
            /* if(!isset( $row['mother_last_name'] )) {
                throw ValidationException::withMessages(['data' => 'La columna (mother_last_name) no existe']);
            } else */
            /* if(!isset( $row['document_type'] )) {
                throw ValidationException::withMessages(['data' => 'La columna (document_type) no existe']);
            } else */
            /* if(!isset( $row['document'] )) {
                throw ValidationException::withMessages(['data' => 'La columna (document) no existe']);
            } else */
            /* if(!isset( $row['telephone'] )) {
                throw ValidationException::withMessages(['data' => 'La columna (telephone) no existe']);
            } else */
            if(!isset( $row['institutional_email'] )) {
                throw ValidationException::withMessages(['data' => 'La columna (institutional_email) no existe']);
            } else
            /* if(!isset( $row['zone'] )) {
                throw ValidationException::withMessages(['data' => 'La columna (zone) no existe']);
            } else */
            /* if(!isset( $row['address'] )) {
                throw ValidationException::withMessages(['data' => 'La columna (address) no existe']);
            } else */
            /* if(!isset( $row['health_manager'] )) {
                throw ValidationException::withMessages(['data' => 'La columna (health_manager) no existe']);
            } else */
            /* if(!isset( $row['residence_city'] )) {
                throw ValidationException::withMessages(['data' => 'La columna (residence_city) no existe']);
            } else */
            /* if(!isset( $row['expedition_city'] )) {
                throw ValidationException::withMessages(['data' => 'La columna (expedition_city) no existe']);
            } else */
            /* if(!isset( $row['birth_city'] )) {
                throw ValidationException::withMessages(['data' => 'La columna (birth_city) no existe']);
            } else */
            /* if(!isset( $row['birthdate'] )) {
                throw ValidationException::withMessages(['data' => 'La columna (birthdate) no existe']);
            } else */
            /* if(!isset( $row['gender'] )) {
                throw ValidationException::withMessages(['data' => 'La columna (gender) no existe']);
            } else */
            /* if(!isset( $row['rh'] )) {
                throw ValidationException::withMessages(['data' => 'La columna (rh) no existe']);
            } else */
            /* if(!isset( $row['conflict_victim'] )) {
                throw ValidationException::withMessages(['data' => 'La columna (conflict_victim) no existe']);
            } else */
            /* if(!isset( $row['number_siblings'] )) {
                throw ValidationException::withMessages(['data' => 'La columna (number_siblings) no existe']);
            } else */
            /* if(!isset( $row['sisben'] )) {
                throw ValidationException::withMessages(['data' => 'La columna (sisben) no existe']);
            } else */
            /* if(!isset( $row['social_stratum'] )) {
                throw ValidationException::withMessages(['data' => 'La columna (social_stratum) no existe']);
            } else */
            /* if(!isset( $row['lunch'] )) {
                throw ValidationException::withMessages(['data' => 'La columna (lunch) no existe']);
            } else */
            /* if(!isset( $row['refreshment'] )) {
                throw ValidationException::withMessages(['data' => 'La columna (refreshment) no existe']);
            } else */
            /* if(!isset( $row['transport'] )) {
                throw ValidationException::withMessages(['data' => 'La columna (transport) no existe']);
            } else */
            /* if(!isset( $row['ethnic_group'] )) {
                throw ValidationException::withMessages(['data' => 'La columna (ethnic_group) no existe']);
            } else */
            /* if(!isset( $row['disability'] )) {
                throw ValidationException::withMessages(['data' => 'La columna (disability) no existe']);
            } else */
            /* if(!isset( $row['origin_school'] )) {
                throw ValidationException::withMessages(['data' => 'La columna (origin_school) no existe']);
            } else */
            /* if(!isset( $row['school_insurance'] )) {
                throw ValidationException::withMessages(['data' => 'La columna (school_insurance) no existe']);
            } else */
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
                throw ValidationException::withMessages(['data' => 'El primer nombre no puede estar vacio']);
            } else
            if (empty(trim($row['father_last_name']))) {
                throw ValidationException::withMessages(['data' => 'El apellido paterno no puede estar vacio']);
            } else
            if (empty(trim($row['institutional_email']))) {
                throw ValidationException::withMessages(['data' => 'El correo institucional no puede estar vacio']);
            } else
            if (empty(trim($row['document_type']))) {
                throw ValidationException::withMessages(['data' => 'El tipo de documento no puede estar vacio']);
            } else
            if (empty(trim($row['document']))) {
                throw ValidationException::withMessages(['data' => 'El documento no puede estar vacio']);
            } else
            if (empty(trim($row['headquarters']))) {
                throw ValidationException::withMessages(['data' => 'La sede no puede estar vacio']);
            } else
            if (empty(trim($row['study_time']))) {
                throw ValidationException::withMessages(['data' => 'La jornada no puede estar vacio']);
            } else
            if (empty(trim($row['study_year']))) {
                throw ValidationException::withMessages(['data' => 'El año de estudio no puede estar vacio']);
            }


            /* Formating data */
            if($row['birthdate']) {
                $row['birthdate'] = Date::excelToDateTimeObject($row['birthdate'])->format('Y-m-d');
            }


            /*
             * Validating that the email is unique.
             */
            $user = User::where('email', $row['institutional_email'])->first();

            if ($user) {
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
            ]);

            Student::create([
                'id'                    => $newUser->id,
                'first_name'            => $row['first_name'],
                'second_name'           => $row['second_name'],
                'father_last_name'      => $row['father_last_name'],
                'mother_last_name'      => $row['mother_last_name'],
                'document_type_code'    => $row['document_type'],
                'document'              => $row['document'],
                'telephone'             => $row['telephone'],
                'institutional_email'   => $row['institutional_email'],
                'zone'                  => $row['zone'],
                'address'               => $row['address'],
                'health_manager_id'     => $row['health_manager'],
                'residence_city_id'     => $row['residence_city'],
                'expedition_city_id'    => $row['expedition_city'],
                'birth_city_id'         => $row['birth_city'],
                'birthdate'             => $row['birthdate'],
                'gender_id'             => $row['gender'],
                'rh_id'                 => $row['rh'],
                'conflict_victim'       => $row['conflict_victim'],
                'number_siblings'       => $row['number_siblings'],
                'sisben_id'             => $row['sisben'],
                'social_stratum'        => $row['social_stratum'],
                // 'lunch'                 => $row['lunch'],
                // 'refreshment'           => $row['refreshment'],
                // 'transport'             => $row['transport'],
                'ethnic_group_id'       => $row['ethnic_group'],
                // 'disability'            => $row['disability'],
                'origin_school_id'      => $row['origin_school'],
                'school_insurance'      => $row['school_insurance'],
                'headquarters_id'       => $row['headquarters'],
                'study_time_id'         => $row['study_time'],
                'study_year_id'         => $row['study_year']
            ]);
        }
    }
}
