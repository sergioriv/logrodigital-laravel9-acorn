<?php

namespace App\Http\Controllers;

use App\Http\Controllers\support\Notify;
use App\Models\Coordination;
use App\Models\Orientation;
use App\Models\Student;
use App\Models\StudentTracking;
use App\Models\StudentTrackingAdvice;
use App\Models\StudentTrackingCoordination;
use App\Models\StudentTrackingFamily;
use App\Models\StudentTrackingRemit;
use App\Models\StudentTrackingTeacher;
use Barryvdh\DomPDF\Facade\Pdf;
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
            'user_id' => Auth::id(),
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
            'reason_entity' => ['required', 'string', 'min:10', 'max:5000'],
            'orientation_intervention' => ['required', 'string', 'min:10', 'max:5000']
        ]);

        StudentTrackingRemit::create([
            'user_id' => Auth::id(),
            'student_id' => $student->id,
            'type_tracking' => 'REMIT',
            'entity_remit' => $request->entity_remit,
            'reason_entity' => $request->reason_entity,
            'orientation_intervention' => $request->orientation_intervention
        ]);

        self::tab();
        Notify::success(__("Remission created!"));
        return redirect()->route('students.show', $student);
    }

    public function teachers_store(Request $request, Student $student)
    {
        $request->validate([
            'recommendations_teachers' => ['required', 'string', 'min:10', 'max:5000'],
            'priority_teacher' => ['nullable', 'boolean']
        ]);


        DB::beginTransaction();
        $tracking = StudentTrackingTeacher::create([
            'user_id' => Auth::id(),
            'student_id' => $student->id,
            'type_tracking' => 'TEACHERS',
            'recommendations_teachers' => $request->recommendations_teachers,
        ]);

        /* Create alert for User Teacher */
        $alert = UserAlertController::orientation_to_teacher($student, $request);
        if ( is_string($alert) ) {
            return redirect()->back()->withErrors($alert);
        }

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
            'recommendations_coordinator' => ['required', 'string', 'min:10', 'max:5000'],
            'priority_coordinator' => ['nullable', 'boolean']
        ]);

        $uuidCoordination = Coordination::where('uuid', $request->trackingCoordinator)->first();

        DB::beginTransaction();
        $tracking = StudentTrackingCoordination::create([
            'user_id' => Auth::id(),
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
            'recommendations_family' => ['required', 'string', 'min:10', 'max:5000']
        ]);

        StudentTrackingFamily::create([
            'user_id' => Auth::id(),
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
            'evolution' => ["required", 'string', 'min:10', 'max:5000']
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
                $content = '<p>'.$tracking->recommendations_teachers.'</p>';
                break;

            case 'coordination':
                $title = __('Recommendation to coordination');
                $content = '<div class="logro-label font-weight-bold">'.__('date').':</div>'.$tracking->created_at.'<br />'
                            .'<div class="logro-label font-weight-bold mt-3">'. __('coordinator') .':</div>'
                            .$tracking->coordination->getFullName().'<br />'
                            .'<div class="logro-label font-weight-bold mt-3">'. __('recommendation') .':</div>'
                            .$tracking->recommendations_coordination;
                break;

            case 'remit':
                $title = __('Remit');
                $content = '<p>'.$tracking->entity_remit.'</p>'
                            .'<b><li>' . __('Reason for remit') . '</li></b>'
                            .'<p>'.$tracking->reason_entity.'</p>'
                            .'<b><li>' . __('Orientation Intervention') . '</li></b>'
                            .'<p>'.$tracking->orientation_intervention.'</p>'
                            .'<div class="text-center mt-6"><a class="btn btn-background hover-outline mb-1" href="' .
                            route('student.tracking.download', $tracking->id)
                            . '">Descargar remisión</a></div>';
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


    public function download_tracking(StudentTracking $tracking)
    {

        switch ($tracking->type_tracking) {
            case 'advice':
                return self::downloadTrackingAdvice($tracking);
                break;

            case 'remit':
                return self::downloadTrackingRemit($tracking);
                break;
        }

        return false;
    }

    private function downloadTrackingRemit($tracking)
    {
        $SCHOOL = SchoolController::myschool()->getData();

        $pdf = Pdf::loadView('logro.pdf.remit', [
            'SCHOOL' => $SCHOOL,
            'date' => now(),
            'tracking' => $tracking,
            'nationalCountry' => NationalCountry::country()
        ])->setPaper('letter', 'portrait')->setOption('dpi', 72);


        return $pdf->download('Remisión ' . $tracking->entity_remit . ' - ' . now() . '.pdf');
    }

    private function downloadTrackingAdvice($tracking)
    {
        $SCHOOL = SchoolController::myschool()->getData();

        try {

            if ( is_null($tracking->evolution) ) {

                /*
                *
                * Asesorias sin evolución, descarga la citación
                *
                * */

                $pdf = Pdf::loadView('logro.pdf.advice-citation', [
                    'SCHOOL' => $SCHOOL,
                    'date' => now()->format('Y / m / d'),
                    'tracking' => $tracking
                ])->setPaper('letter', 'portrait')->setOption('dpi', 72);

                return $pdf->download('Citación ' . $tracking->student->getCompleteNames() . ' - ' . $tracking->dateFull() . '.pdf');

            } else {

                /*
                *
                * Descarga la evolucion de la asesoría
                *
                * */
                $pdf = Pdf::loadView('logro.pdf.evolucion-citation', [
                    'SCHOOL' => $SCHOOL,
                    'date' => now()->format('Y / m / d'),
                    'tracking' => $tracking
                ])->setPaper('letter', 'portrait')->setOption('dpi', 72);

                return $pdf->download('Evolución ' . $tracking->student->getCompleteNames() . ' - ' . $tracking->dateFull() . '.pdf');

            }
        } catch (\Throwable $e) {
            Notify::fail(__('An error has occurred'));
            $this->tab();
            return back();
        }
    }

    private function tab()
    {
        session()->flash('tab', 'tracking');
    }
}
