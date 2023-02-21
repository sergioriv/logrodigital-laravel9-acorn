<?php

namespace App\Http\Controllers;

use App\Http\Controllers\support\Notify;
use App\Models\Student;
use App\Models\Voting;
use App\Models\VotingCandidate;
use App\Models\VotingStudent;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class VotingSystemGuestController extends Controller
{
    public function __construct()
    {

    }

    public function toVote()
    {
        return view('logro.voting.to-vote');
    }

    public function toStart(Request $request)
    {
        $request->validate([
            'document' => ['required', 'exists:students,document']
        ], [
            'exists' => 'Documento no registrado'
        ]);

        $Y = SchoolYearController::available_year();

        $student = Student::select('id')->where('document', $request->document)->first();
        $myVotes = VotingStudent::whereHas(
            'voting',
            fn ($vt) => $vt->where('school_year_id', $Y->id)
        )->where('student_id', $student->id)->get()->pluck('voting_id')->toArray();

        $votingActive = Voting::select('id')->whereHas(
            'constituencies',
            function ($constituencies) use ($student) {
                $constituencies->whereHas(
                    'group',
                    function ($groups) use ($student) {
                        $groups->whereHas(
                            'groupStudents',
                            function ($groupStudents) use ($student) {
                                $groupStudents->where('student_id', $student->id);
                            }
                        );
                    }
                );
            }
        )->where('school_year_id', $Y->id)
            ->where('status', 2)
            ->pluck('id')->toArray();


        $missing = array_values(array_diff($votingActive, $myVotes));

        if ( count($myVotes) === 0 && count($missing) === 0 ) {
            Notify::fail('No tiene votaciones activas');
            return redirect()->route('voting.to-vote');
        }

        if ( count($myVotes) > 0 && count($missing) === 0 ) {
            Notify::success('¡FELICIDADES! has terminado las votaciones');
            return redirect()->route('voting.to-vote');
        }



        $voting = Voting::where('id', $missing[0])
            ->with([
                'candidates' =>
                fn ($candidates) => $candidates->with(['student' => ['group:id,name']])
            ])->first();

        /* Conteo de votaciones */
        $countVoting = count($myVotes) + 1 . ' / ' . count($votingActive);

        // return $voting;

        return view('logro.voting.voting-card', [
            'document' => $request->document,
            'countVoting' => $countVoting,
            'voting' => $voting
        ]);
    }

    public function saveVote(Request $request)
    {
        $request->validate([
            'vote' => ['required'],
            'document' => ['required', Rule::exists('students', 'document')],
            'voting' => ['required', Rule::exists('voting', 'id')]
        ]);

        $Y = SchoolYearController::available_year();

        $student = Student::select('id')->where('document', $request->document)->first();

        $voting = Voting::where('id', $request->voting)
            ->whereHas(
                'constituencies',
                function ($constituencies) use ($student) {
                    $constituencies->whereHas(
                        'group',
                        function ($group) use ($student) {
                            $group->whereHas(
                                'groupStudents',
                                function ($groupStudents) use ($student) {
                                    $groupStudents->where('student_id', $student->id);
                                }
                            );
                        }
                    );
                }
            )
            ->whereNot(function ($not) use ($student) {
                $not->whereHas(
                    'voteStudents',
                    function ($voteStudents) use ($student) {
                        $voteStudents->where('student_id', $student->id);
                    }
                );
            })

            ->first();


        if (is_null($voting)) {
            Notify::fail(__('Not allowed'));
            return redirect()->route('voting.to-vote');
        }


        if (!$voting->status->isStarted() || $voting->school_year_id != $Y->id) {
            Notify::info(__(':VOTING voting ended', ['VOTING' => $voting->title]));
            return redirect()->route('voting.to-vote');
        }



        /* Comprobar el que candidato votado existe */
        $checkCandidate = NULL;
        if ( $request->vote !== 'BLANK' ) {
            $checkCandidate = VotingCandidate::where('voting_id', $voting->id)
                ->where('id', $request->vote)
                ->count();

            if ( ! $checkCandidate ) {
                Notify::fail('Candidato no válido');
                return back();
            }
        }


        VotingStudent::create([
            'voting_id' => $voting->id,
            'student_id' => $student->id,
            'voted_for' => $request->vote
        ]);

        Notify::success('Voto guardado!');
        return back();
    }
}
