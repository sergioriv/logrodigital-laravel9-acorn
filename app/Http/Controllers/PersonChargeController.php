<?php

namespace App\Http\Controllers;

use App\Http\Controllers\support\UserController;
use App\Models\PersonCharge;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PersonChargeController extends Controller
{

    function __construct()
    {
        $this->middleware('can:students.info');
    }

    public function update (Student $student, Request $request)
    {
        $mother = PersonCharge::where('id', $request->mother)
            ->where('kinship_id',1)
            ->where('student_id', $student->id)
            ->first();
        $father = PersonCharge::where('id', $request->father)
            ->where('kinship_id',2)
            ->where('student_id', $student->id)
            ->first();
        $tutor = PersonCharge::where('id', $request->tutor)
            ->where('kinship_id','>',2)
            ->where('student_id', $student->id)
            ->first();


        if ($mother !== null)
        {
            $mother_id = $mother->id;
        } else
        {
            $mother_id = null;
        }
        if ($father !== null)
        {
            $father_id = $father->id;
        } else
        {
            $father_id = null;
        };
        if ($tutor !== null)
        {
            $tutor_id = $tutor->id;
        } else
        {
            $tutor_id = null;
        };

        $request->validate([
            /* MOTHER */
            'mother_name' => ['nullable', 'string', 'max:191'],
            'mother_email' => ['nullable', 'max:191', 'email', Rule::unique('users','email')->ignore($mother_id)],
            'mother_document' => ['nullable', 'string', 'max:20'],
            'mother_expedition_city' => ['nullable', Rule::exists('cities','id')],
            'mother_residence_city' => ['nullable', Rule::exists('cities','id')],
            'mother_address' => ['nullable', 'string', 'max:100'],
            'mother_telephone' => ['nullable', 'string', 'max:20'],
            'mother_cellphone' => ['nullable', 'string', 'max:20'],
            'mother_birthdate' => ['nullable', 'date'],
            'mother_occupation' => ['nullable', 'string', 'max:191'],

            /* FATHER */
            'father_name' => ['nullable', 'string', 'max:191'],
            'father_email' => ['nullable', 'max:191', 'email', Rule::unique('users','email')->ignore($father_id)],
            'father_document' => ['nullable', 'string', 'max:20'],
            'father_expedition_city' => ['nullable', Rule::exists('cities','id')],
            'father_residence_city' => ['nullable', Rule::exists('cities','id')],
            'father_address' => ['nullable', 'string', 'max:100'],
            'father_telephone' => ['nullable', 'string', 'max:20'],
            'father_cellphone' => ['nullable', 'string', 'max:20'],
            'father_birthdate' => ['nullable', 'date'],
            'father_occupation' => ['nullable', 'string', 'max:191'],

            /* FATHER */
            'tutor_name' => ['nullable', 'string', 'max:191'],
            'tutor_email' => ['nullable', 'max:191', 'email', Rule::unique('users','email')->ignore($tutor_id)],
            'tutor_document' => ['nullable', 'string', 'max:20'],
            'tutor_expedition_city' => ['nullable', Rule::exists('cities','id')],
            'tutor_residence_city' => ['nullable', Rule::exists('cities','id')],
            'tutor_address' => ['nullable', 'string', 'max:100'],
            'tutor_telephone' => ['nullable', 'string', 'max:20'],
            'tutor_cellphone' => ['nullable', 'string', 'max:20'],
            'tutor_birthdate' => ['nullable', 'date'],
            'tutor_occupation' => ['nullable', 'string', 'max:191'],

            /* PERSON CHARGE */
            'person_charge' => ['required', Rule::exists('kinships','id')]
        ]);

        /*
         * Create or Update Mother User
         */
        if ( $mother === NULL )
        {
            $user_mother = UserController::_create($request->mother_name, $request->mother_email, 8);
            PersonCharge::create([
                'id' => $user_mother->id,
                'student_id' => $student->id,
                'name' => $request->mother_name,
                'email' => $request->mother_email,
                'document' => $request->mother_document,
                'expedition_city_id' => $request->mother_expedition_city,
                'residence_city_id' => $request->mother_residence_city,
                'address' => $request->mother_address,
                'telephone' => $request->mother_telephone,
                'cellphone' => $request->mother_cellphone,
                'birthdate' => $request->mother_birthdate,
                'kinship_id' => 1,
                'occupation' => $request->mother_occupation
            ]);
        } else
        {
            UserController::_update($mother->id, $request->mother_name);
            $mother->update([
                'name' => $request->mother_name,
                'document' => $request->mother_document,
                'expedition_city_id' => $request->mother_expedition_city,
                'residence_city_id' => $request->mother_residence_city,
                'address' => $request->mother_address,
                'telephone' => $request->mother_telephone,
                'cellphone' => $request->mother_cellphone,
                'birthdate' => $request->mother_birthdate,
                'occupation' => $request->mother_occupation
            ]);
        }

        /*
         * Create or Update Father User
         */
        if ( $father === NULL )
        {
            $user_father = UserController::_create($request->father_name, $request->father_email, 8);
            PersonCharge::create([
                'id' => $user_father->id,
                'student_id' => $student->id,
                'name' => $request->father_name,
                'email' => $request->father_email,
                'document' => $request->father_document,
                'expedition_city_id' => $request->father_expedition_city,
                'residence_city_id' => $request->father_residence_city,
                'address' => $request->father_address,
                'telephone' => $request->father_telephone,
                'cellphone' => $request->father_cellphone,
                'birthdate' => $request->father_birthdate,
                'kinship_id' => 2,
                'occupation' => $request->father_occupation
            ]);
        } else
        {
            UserController::_update($father->id, $request->father_name);
            $father->update([
                'name' => $request->father_name,
                'document' => $request->father_document,
                'expedition_city_id' => $request->father_expedition_city,
                'residence_city_id' => $request->father_residence_city,
                'address' => $request->father_address,
                'telephone' => $request->father_telephone,
                'cellphone' => $request->father_cellphone,
                'birthdate' => $request->father_birthdate,
                'occupation' => $request->father_occupation
            ]);
        }

        /*
         * Create or Update Tutor User
         */
        if( $request->person_charge > 2 )
        {
            if ( $tutor === NULL )
            {
                $user_tutor = UserController::_create($request->tutor_name, $request->tutor_email, 8);
                PersonCharge::create([
                    'id' => $user_tutor->id,
                    'student_id' => $student->id,
                    'name' => $request->tutor_name,
                    'email' => $request->tutor_email,
                    'document' => $request->tutor_document,
                    'expedition_city_id' => $request->tutor_expedition_city,
                    'residence_city_id' => $request->tutor_residence_city,
                    'address' => $request->tutor_address,
                    'telephone' => $request->tutor_telephone,
                    'cellphone' => $request->tutor_cellphone,
                    'birthdate' => $request->tutor_birthdate,
                    'kinship_id' => $request->person_charge,
                    'occupation' => $request->tutor_occupation
                ]);
            } else
            {
                UserController::_update($tutor->id, $request->tutor_name);
                $tutor->update([
                    'name' => $request->tutor_name,
                    'document' => $request->tutor_document,
                    'expedition_city_id' => $request->tutor_expedition_city,
                    'residence_city_id' => $request->tutor_residence_city,
                    'address' => $request->tutor_address,
                    'telephone' => $request->tutor_telephone,
                    'cellphone' => $request->tutor_cellphone,
                    'birthdate' => $request->tutor_birthdate,
                    'kinship_id' => $request->person_charge,
                    'occupation' => $request->tutor_occupation
                ]);
            }
        } else
        {
            if ( NULL !== $tutor )
            {
                UserController::delete_user($tutor->id);
            }
        }

        $student->update([
            'person_charge' => $request->person_charge
        ]);

        return redirect()->back()->with(
            ['notify' => 'success', 'title' => __('Student updated!')],
        );
    }
}
