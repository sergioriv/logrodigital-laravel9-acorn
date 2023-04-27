<?php

namespace App\Http\Controllers;

use App\Http\Controllers\support\Notify;
use App\Http\Controllers\support\UserController;
use App\Models\Data\RoleUser;
use App\Models\PersonCharge;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PersonChargeController extends Controller
{

    function __construct()
    {
        $this->middleware('can:students.info');
    }

    public function update(Student $student, Request $request, $wizard = false)
    {
        $mother = PersonCharge::where('id', $request->mother)
            ->where('kinship_id', 1)
            ->where('student_id', $student->id)
            ->first();
        $father = PersonCharge::where('id', $request->father)
            ->where('kinship_id', 2)
            ->where('student_id', $student->id)
            ->first();
        $tutor = PersonCharge::where('id', $request->tutor)
            ->where('kinship_id', '>', 2)
            ->where('student_id', $student->id)
            ->first();


        // if ($mother !== null) {
        //     $mother_id = $mother->id;
        // } else {
        //     $mother_id = null;
        // }
        // if ($father !== null) {
        //     $father_id = $father->id;
        // } else {
        //     $father_id = null;
        // }
        // if ($tutor !== null) {
        //     $tutor_id = $tutor->id;
        // } else {
        //     $tutor_id = null;
        // }
        $mother_id = null;
        $father_id = null;
        $tutor_id = null;

        $request->validate([
            /* PERSON CHARGE */
            'person_charge' => ['required', Rule::exists('kinships', 'id')]
        ]);

        $mother_required = 'nullable';
        $father_required = 'nullable';
        $tutor_required = 'nullable';
        if (1 == $request->person_charge) {
            $mother_required = 'required';
        } elseif (2 == $request->person_charge) {
            $father_required = 'required';
        } else {
            $tutor_required = 'required';
        }

        $request->validate([
            /* MOTHER */
            'mother_name' => [$mother_required, 'string', 'max:191'],
            'mother_email' => ['nullable', 'max:191', 'email'], // Rule::unique('users','email')->ignore($mother_id)
            'mother_document' => [$mother_required, 'string', 'max:20'],
            'mother_expedition_city' => [$mother_required, Rule::exists('cities', 'id')],
            'mother_residence_city' => [$mother_required, Rule::exists('cities', 'id')],
            'mother_address' => [$mother_required, 'string', 'max:100'],
            'mother_telephone' => [$mother_required, 'string', 'max:20'],
            'mother_cellphone' => [$mother_required, 'string', 'max:20'],
            'mother_birthdate' => [$mother_required, 'date', 'date_format:Y-m-d'],
            'mother_occupation' => [$mother_required, 'string', 'max:191'],

            /* FATHER */
            'father_name' => [$father_required, 'string', 'max:191'],
            'father_email' => ['nullable', 'max:191', 'email'], // Rule::unique('users','email')->ignore($father_id)
            'father_document' => [$father_required, 'string', 'max:20'],
            'father_expedition_city' => [$father_required, Rule::exists('cities', 'id')],
            'father_residence_city' => [$father_required, Rule::exists('cities', 'id')],
            'father_address' => [$father_required, 'string', 'max:100'],
            'father_telephone' => [$father_required, 'string', 'max:20'],
            'father_cellphone' => [$father_required, 'string', 'max:20'],
            'father_birthdate' => [$father_required, 'date', 'date_format:Y-m-d'],
            'father_occupation' => [$father_required, 'string', 'max:191'],

            /* FATHER */
            'tutor_name' => [$tutor_required, 'string', 'max:191'],
            'tutor_email' => ['nullable', 'max:191', 'email'], // Rule::unique('users','email')->ignore($tutor_id)
            'tutor_document' => [$tutor_required, 'string', 'max:20'],
            'tutor_expedition_city' => [$tutor_required, Rule::exists('cities', 'id')],
            'tutor_residence_city' => [$tutor_required, Rule::exists('cities', 'id')],
            'tutor_address' => [$tutor_required, 'string', 'max:100'],
            'tutor_telephone' => [$tutor_required, 'string', 'max:20'],
            'tutor_cellphone' => [$tutor_required, 'string', 'max:20'],
            'tutor_birthdate' => [$tutor_required, 'date', 'date_format:Y-m-d'],
            'tutor_occupation' => [$tutor_required, 'string', 'max:191']

        ]);

        /*
         * Create or Update Mother User
         */
        DB::beginTransaction();
        $sendEmailMother = false;

        if (NULL !== $request->mother_name) {
            if ($mother === NULL) {

                $motherCreate = UserController::__create($request->mother_name, $request->mother_email, RoleUser::PARENT);

                if (!$motherCreate->getUser()) {

                    DB::rollBack();
                    Notify::fail(__('Something went wrong.'));
                    return redirect()->back();
                }

                try {

                    $mother = PersonCharge::create([
                        'id' => $motherCreate->getUser()->id,
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

                    $mother_id = $motherCreate->getUser()->id;
                    $sendEmailMother = true;
                } catch (\Throwable $th) {

                    DB::rollBack();
                    Notify::fail(__('Something went wrong.'));
                    return redirect()->back();
                }
            } else {

                try {

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

                    $mother_id = $mother->id;

                } catch (\Throwable $th) {

                    DB::rollBack();
                    Notify::fail(__('Something went wrong.'));
                    return redirect()->back();
                }
            }
        }

        if ($sendEmailMother) {

            if (!$motherCreate->sendVerification()) {

                DB::rollBack();
                Notify::fail(__('Invalid email (:email)', ['email' => $request->mother_email]));
                return redirect()->back();
            }
        }

        DB::commit();


        /*
         * Create or Update Father User
         */
        DB::beginTransaction();
        $sendEmailFather = false;

        if (NULL !== $request->father_name) {
            if ($father === NULL) {


                $fatherCreate = UserController::__create($request->father_name, $request->father_email, RoleUser::PARENT);

                if (!$fatherCreate->getUser()) {

                    DB::rollBack();
                    Notify::fail(__('Something went wrong.'));
                    return redirect()->back();
                }

                try {

                    $father = PersonCharge::create([
                        'id' => $fatherCreate->getUser()->id,
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

                    $father_id = $fatherCreate->getUser()->id;
                    $sendEmailFather = true;
                } catch (\Throwable $th) {

                    DB::rollBack();
                    Notify::fail(__('Something went wrong.'));
                    return redirect()->back();
                }
            } else {

                try {

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

                    $father_id = $father->id;
                } catch (\Throwable $th) {

                    DB::rollBack();
                    Notify::fail(__('Something went wrong.'));
                    return redirect()->back();
                }
            }
        }

        if ($sendEmailFather) {

            if (!$fatherCreate->sendVerification()) {

                DB::rollBack();
                Notify::fail(__('Invalid email (:email)', ['email' => $request->father_email]));
                return redirect()->back();
            }
        }

        DB::commit();



        /*
         * Create or Update Tutor User
         */

        if ($request->person_charge > 2) {

            DB::beginTransaction();
            $sendEmailTutor = false;

            if (NULL !== $request->tutor_name) {
                if ($tutor === NULL) {


                    $tutorCreate = UserController::__create($request->tutor_name, $request->tutor_email, RoleUser::PARENT);

                    if (!$tutorCreate->getUser()) {

                        DB::rollBack();
                        Notify::fail(__('Something went wrong.'));
                        return redirect()->back();
                    }

                    try {

                        $tutor = PersonCharge::create([
                            'id' => $tutorCreate->getUser()->id,
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

                        $tutor_id = $tutorCreate->getUser()->id;
                        $sendEmailTutor = true;
                    } catch (\Throwable $th) {

                        DB::rollBack();
                        Notify::fail(__('Something went wrong.'));
                        return redirect()->back();
                    }
                } else {

                    try {

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

                        $tutor_id = $tutor->id;
                    } catch (\Throwable $th) {

                        DB::rollBack();
                        Notify::fail(__('Something went wrong.'));
                        return redirect()->back();
                    }
                }
            }

            if ($sendEmailTutor) {
                if (!$tutorCreate->sendVerification()) {

                    DB::rollBack();
                    Notify::fail(__('Invalid email (:email)', ['email' => $request->tutor_email]));
                    return redirect()->back();
                }
            }

            DB::commit();


        } else {
            if (NULL !== $tutor) {
                UserController::delete_user($tutor->id);
            }
        }





        $student->update([
            'person_charge' => match($request->person_charge){
                "1" => $mother_id,
                "2" => $father_id,
                default => $tutor_id
            }
        ]);

        if ($wizard === TRUE) {
            $student->forceFill([
                'wizard_person_charge' => TRUE
            ])->save();

            return redirect()->back()->with('student', $student);
        } else {
            Notify::success(__('Student updated!'));
            return redirect()->back();
        }
    }
}
