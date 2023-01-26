<?php

namespace App\Imports;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Str;

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
            if(!isset( $row['names'] )) {
                throw ValidationException::withMessages(['data' => 'La columna (names) no existe']);
            } else
            if(!isset( $row['last_names'] )) {
                throw ValidationException::withMessages(['data' => 'La columna (last_names) no existe']);
            } else
            if(!isset( $row['email'] )) {
                throw ValidationException::withMessages(['data' => 'La columna (email) no existe']);
            }


            /*
             * Validating that the email is not empty.
             */
            if (empty(trim($row['names']))) {
                throw ValidationException::withMessages(['data' => 'hay un (names) vacio']);
            }
            if (empty(trim($row['last_names']))) {
                throw ValidationException::withMessages(['data' => 'hay un (last_names) vacio']);
            }
            if (empty(trim($row['email']))) {
                throw ValidationException::withMessages(['data' => 'hay un (email) vacio']);
            }


            /*
             * Validating that the email is unique.
             */
            $row['email'] = Str::lower($row['email']);
            $validEmail = User::where('email', $row['email'])->first();

            if ($validEmail) {
                throw ValidationException::withMessages(['data' => 'El correo (' . $row['email'] . ') ya existe!']);
            }


            /*
             * Creating a new user and a new teacher.
             */
            $newUser = new User();
            $newUser->forceFill([
                'name'     => $row['names'],
                'email'    => $row['email'],
                'email_verified_at' => now(),
                'password' => Hash::make('123456')
            ])->save();
            $newUser->assignRole(6);

            Teacher::create([
                'id'                    => $newUser->id,
                'names'                 => $row['names'],
                'last_names'            => $row['last_names'],
                'telephone'             => $row['phone_number'],
                'institutional_email'   => $row['email']
            ]);
        }
    }
}
