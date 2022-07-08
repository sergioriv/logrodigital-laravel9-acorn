<?php

namespace App\Http\Controllers;

use App\Http\Controllers\support\UserController;
use App\Models\PersonCharge;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PersonChargeController extends Controller
{
    public function update (Student $student, Request $request)
    {
        $mother = PersonCharge::find($request->mother);
        $father = PersonCharge::find($request->father);

        if ($mother !== null)
        {
            $mother_id = $mother->id;
            $mother_email_required = 'nullable';
        } else
        {
            $mother_id = null;
            $mother_email_required = ['required','email',Rule::unique('users','email')->ignore($mother_id)];
        }
        if ($father !== null)
        {
            $father_id = $father->id;
            $father_email_required = 'nullable';
        } else
        {
            $father_id = null;
            $father_email_required = ['required','email',Rule::unique('users','email')->ignore($father_id)];
        };

        $request->validate([
            /* MOTHER */
            'mother_name' => ['required','string'],
            'mother_email' => $mother_email_required,
            'mother_document' => ['nullable', 'string'],
            'mother_expedition_city' => ['nullable',Rule::exists('cities','id')],
            'mother_residence_city' => ['nullable',Rule::exists('cities','id')],
            'mother_address' => ['nullable','string'],
            'mother_telephone' => ['nullable','string'],
            'mother_cellphone' => ['required','string'],
            'mother_birthdate' => ['nullable','date'],
            'mother_occupation' => ['nullable','string'],

            /* FATHER */
            'father_name' => ['required','string'],
            'father_email' => $father_email_required,
            'father_document' => ['nullable', 'string'],
            'father_expedition_city' => ['nullable',Rule::exists('cities','id')],
            'father_residence_city' => ['nullable',Rule::exists('cities','id')],
            'father_address' => ['nullable','string'],
            'father_telephone' => ['nullable','string'],
            'father_cellphone' => ['required','string'],
            'father_birthdate' => ['nullable','date'],
            'father_occupation' => ['nullable','string'],

            /* PERSON CHARGE */
            'person_charge' => ['required',Rule::exists('kinships','id')]
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

        $student->update([
            'person_charge' => $request->person_charge
        ]);

        return redirect()->back()->with(
            ['notify' => 'success', 'title' => __('Student updated!')],
        );
    }
}
