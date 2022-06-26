<?php

namespace App\Imports;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TeachersImport implements ToCollection, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function collection(Collection $rows)
    {
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
            if(!isset( $row['email'] )) {
                throw ValidationException::withMessages(['data' => 'La columna (email) no existe']);
            }


            /*
             * Validating that the email is not empty.
             */
            if (empty(trim($row['email']))) {
                throw ValidationException::withMessages(['data' => 'El correo no puede estar vacio']);
            }


            /*
             * Validating that the email is unique.
             */
            $user = User::where('email', $row['email'])->first();

            if ($user) {
                throw ValidationException::withMessages(['data' => 'El correo (' . $row['email'] . ') ya existe!']);
            }


            /*
             * Creating a new user and a new teacher.
             */
            $newUser = User::create([
                'name'     => $row['first_name'] . ' ' . $row['father_last_name'],
                'email'    => $row['email'],
            ]);

            Teacher::create([
                'id'                    => $newUser->id,
                'first_name'            => $row['first_name'],
                'second_name'           => $row['second_name'],
                'father_last_name'      => $row['father_last_name'],
                'mother_last_name'      => $row['mother_last_name'],
                'telephone'             => $row['phone_number'],
                'institutional_email'   => $row['email']
            ]);
        }
    }
}
