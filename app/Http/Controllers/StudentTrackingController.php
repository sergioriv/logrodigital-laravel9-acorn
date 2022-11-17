<?php

namespace App\Http\Controllers;

use App\Http\Controllers\support\Notify;
use App\Http\Controllers\support\UserController;
use App\Models\Coordination;
use App\Models\Orientation;
use App\Models\Student;
use App\Models\StudentTracking;
use App\Models\StudentTrackingAdvice;
use App\Models\StudentTrackingCoordination;
use App\Models\StudentTrackingFamily;
use App\Models\StudentTrackingRemit;
use App\Models\StudentTrackingTeacher;
use App\Models\UserAlert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class StudentTrackingController extends Controller
{
    function __construct()
    {
        $this->middleware('can:students.psychosocial');
    }


    public function advice_store(Request $request, Student $student)
    {
        $request->validate([
            'date' => ['required', 'date'],
            'time' => ['required']
        ]);

        $timeAdvice = Str::substr($request->time, 0,5);

        StudentTrackingAdvice::create([
            'user_id' => Auth::user()->id,
            'student_id' => $student->id,
            'type_tracking' => 'ADVICE',
            'attendance' => 'SCHEDULED',
            'date' => $request->date,
            'time' => $timeAdvice
        ]);

        self::tab();
        Notify::success(__("Advice created!"));
        return redirect()->route('students.show', $student);
    }

    public function remit_store(Request $request, Student $student)
    {
        $request->validate([
            'entity_remit' => ['required', 'min:1', 'max:191'],
            'reason_entity' => ['required', 'string', 'min:10', 'max:1000']
        ]);

        StudentTrackingRemit::create([
            'user_id' => Auth::user()->id,
            'student_id' => $student->id,
            'type_tracking' => 'REMIT',
            'entity_remit' => $request->entity_remit,
            'reason_entity' => $request->reason_entity
        ]);

        self::tab();
        Notify::success(__("Remission created!"));
        return redirect()->route('students.show', $student);
    }

    public function teachers_store(Request $request, Student $student)
    {
        $request->validate([
            'date_limit_teachers' => ['required', 'date'],
            'recommendations_teachers' => ['required', 'string', 'min:10', 'max:1000'],
            'priority_teacher' => ['nullable', 'boolean']
        ]);


        DB::beginTransaction();
        $tracking = StudentTrackingTeacher::create([
            'user_id' => Auth::user()->id,
            'student_id' => $student->id,
            'type_tracking' => 'TEACHERS',
            'recommendations_teachers' => $request->recommendations_teachers,
            'date_limit_teacher' => $request->date_limit_teachers
        ]);

        /* Create alert for User Teacher */
        $alert = UserAlertController::orientation_to_teacher($student, $request);

        if ( $tracking && $alert ) {

            DB::commit();

        } else {

            DB::rollBack();

            self::tab();
            return redirect()->back()->withErrors(__('Unexpected Error'));
        }

        self::tab();
        Notify::success(__("Recommendations created!"));
        return redirect()->route('students.show', $student);
    }

    public function coordination_store(Request $request, Student $student)
    {
        $request->validate([
            'trackingCoordinator' => ['required', Rule::exists((new Coordination)->getTable(), 'uuid')],
            'recommendations_coordinator' => ['required', 'string', 'min:10', 'max:1000'],
            'priority_coordinator' => ['nullable', 'boolean']
        ]);

        $uuidCoordination = Coordination::select('id')->where('uuid', $request->trackingCoordinator)->first();

        DB::beginTransaction();
        $tracking = StudentTrackingCoordination::create([
            'user_id' => Auth::user()->id,
            'student_id' => $student->id,
            'type_tracking' => 'COORDINATION',
            'coordination_id' => $uuidCoordination->id,
            'recommendations_coordination' => $request->recommendations_coordinator
        ]);


        /* Create alert for User Coordinator */
        $alert = UserAlertController::orientation_to_coordinator($uuidCoordination, $student, $request);

        if ( $tracking && $alert ) {

            DB::commit();

        } else {

            DB::rollBack();

            self::tab();
            return redirect()->back()->withErrors(__('Unexpected Error'));
        }


        self::tab();
        Notify::success(__("Recommendations created!"));
        return redirect()->route('students.show', $student);
    }

    public function family_store(Request $request, Student $student)
    {
        $request->validate([
            'recommendations_family' => ['required', 'string', 'min:10', 'max:1000']
        ]);

        StudentTrackingFamily::create([
            'user_id' => Auth::user()->id,
            'student_id' => $student->id,
            'type_tracking' => 'FAMILY',
            'recommendations_family' => $request->recommendations_family
        ]);

        self::tab();
        Notify::success(__("Recommendations created!"));
        return redirect()->route('students.show', $student);
    }


    public function tracking_evolution(Student $student, StudentTrackingAdvice $advice)
    {
        if ( $advice->type_tracking !== 'advice' ) {
            Notify::fail(__('Not allowed'));
            return redirect()->back();
        } elseif ( $advice->evolution === NULL ) {
            return view('logro.student.tracking.evolution', [
                'student' => $student,
                'advice' => $advice
            ]);
        } else {
            return redirect()->back()->withErrors(__('Advice has already evolved'));
        }


    }
    public function tracking_evolution_update(Student $student, StudentTrackingAdvice $advice, Request $request)
    {
        if ($advice->student_id !== $student->id)
            return redirect()->back()->withErrors(__('Unexpected Error'));

        $request->validate([
            'attendance' => ["required"],
            'type_advice' => ["required_if:attendance,done"],
            'evolution' => ["required", 'string', 'min:10', 'max:1000']
        ]);

        $advice->update([
            'attendance' => $request->attendance,
            'type_advice' => $request->type_advice,
            'evolution' => $request->evolution,
        ]);

        self::tab();
        Notify::success(__("Evolved advice!"));
        return redirect()->route('students.show', $student);
    }

    public function tracking_view(Request $request)
    {
        $request->validate(['tracking' => ['required', Rule::exists('student_tracking', 'id')]]);

        $tracking = StudentTracking::find($request->tracking);

        switch ($tracking->type_tracking) {
            case 'family':
                $title = __('recommendation to the family');
                $content = '<p>'.$tracking->recommendations_family.'</p>';
                break;

            case 'teachers':
                $title = __('Recommendation for teachers');
                $content = '<p>'.$tracking->date_limit_teacher.'</p>'
                            .'<p>'.$tracking->recommendations_teachers.'</p>';
                break;

            case 'coordination':
                $title = __('Recommendation to coordination');
                $content = '<div class="logro-label font-weight-bold">'.__('date').':</div>'.$tracking->created_at.'<br />'
                            .'<div class="logro-label font-weight-bold mt-3">'. __('coordinator') .':</div>'
                            .$tracking->coordination->fullName().'<br />'
                            .'<div class="logro-label font-weight-bold mt-3">'. __('recommendation') .':</div>'
                            .$tracking->recommendations_coordination;
                break;

            case 'remit':
                $title = __('Remit');
                $content = '<p>'.$tracking->entity_remit.'</p>'
                            .'<p>'.$tracking->reason_entity.'</p>';
                break;

            case 'advice':
                $title = __('advice');
                $content = '<div class="logro-label font-weight-bold">'.__('date').':</div>'.$tracking->dateFull().'<br />'
                            .'<div class="logro-label font-weight-bold mt-3">'.__('Attendance').':</div>'.__($tracking->attendance).'<br />'
                            .'<div class="logro-label font-weight-bold mt-3">'.__('Type advice').':</div>'.__($tracking->type_advice).'<br />'
                            .'<hr>'
                            .'<p class="m-0">'.$tracking->evolution.'</p>';
                break;

            default:
                $title = null;
                break;
        }

        return ['title' => $title, 'content' => $content];
    }

    // public function show(Student $student, StudentAdvice $advice)
    // {
    //     if ('done' === $advice->attendance)
    //     {
    //         return view('logro.student.advices.show', ['student' => $student, 'advice' => $advice]);
    //     }
    //     if ('scheduled' === $advice->attendance)
    //     {
    //         return 'scheduled';
    //     }

    //     return redirect()->back();
    // }

    // public function edit(Student $student, StudentAdvice $advice)
    // {
    //     return view('logro.student.advices.edit', ['student' => $student, 'advice' => $advice]);
    // }

    // public function update(Request $request, Student $student, StudentAdvice $advice)
    // {
    //     $request->validate([
    //         'attendance' => ["required"],
    //         'type_advice' => ["required_if:attendance,done"],
    //         'evolution' => ["required_if:attendance,done", 'min:10', 'max:500'],
    //         'recommendations_teachers' => ['nullable', 'min:10', 'max:500'],
    //         'date_limite' => ['required_with:recommendations_teachers'],
    //         'recommendations_family' => ['nullable', 'min:10', 'max:500'],
    //         'entity_remit' => ['nullable'],
    //         'observations_for_entity' => ['min:10', 'max:500',
    //             'required_unless:entity_remit,null,Ninguna']
    //     ]);

    //     $typeAdvice = null;
    //     if ($request->has('type_advice'))
    //     {
    //         $typeAdvice = Str::upper($request->type_advice);
    //     }

    //     $entityRemit = null;
    //     if ($request->has('entity_remit'))
    //     {
    //         $entityRemit = Str::upper($request->entity_remit);
    //     }

    //     $advice->update([
    //         'attendance' => Str::upper($request->attendance),
    //         'type_advice' => $typeAdvice,
    //         'evolution' => $request->evolution,
    //         'recommendations_teachers' => $request->recommendations_teachers,
    //         'date_limit_teacher' => $request->date_limite,
    //         'recommendations_family' => $request->recommendations_family,
    //         'entity_remit' => $entityRemit,
    //         'observations_for_entity' => $request->observations_for_entity
    //     ]);

    //     self::tab();
    //     Notify::success(__("Advice save!"));
    //     return redirect()->route('students.show', $student);
    // }


    private function tab()
    {
        session()->flash('tab', 'tracking');
    }
}
