<?php

namespace App\Http\Controllers;

use App\Http\Controllers\support\Notify;
use App\Http\Controllers\support\UserController;
use App\Models\Group;
use App\Models\Headquarters;
use App\Models\Student;
use App\Models\StudyYear;
use App\Models\Teacher;
use App\Models\Voting;
use App\Models\VotingCandidate;
use App\Models\VotingGroup;
use App\Models\VotingStudent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class VotingSystemController extends Controller
{
    protected const VOTING_COORDINATOR = 9;

    public function __construct()
    {
    }

    public function index()
    {
        $Y = SchoolYearController::available_year();

        $voting = Voting::where('school_year_id', $Y->id)
            ->withCount(['candidates'])
            ->with(['constituencies' => fn ($const) => $const->with(['group' => fn ($group) => $group->withCount('groupStudents')])])
            ->orderBy('created_at')
            ->get();

        $votingStarted = Voting::where('school_year_id', $Y->id)->where('status', 2)->count();

        return view('logro.voting.index', [
            'voting' => $voting,
            'votingStarted' => $votingStarted
        ]);
    }

    public function create()
    {
        return view('logro.voting.create', [
            'students'
            => Student::singleData()
                ->with('group')
                ->where('enrolled', 1)
                ->get(),
            'headquarters' => Headquarters::all(),
            'studyYears' => StudyYear::all()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:191'],
            'candidates' => ['required', 'array'],
            'headquarters' => ['required', 'array'],
            'study_years' => ['required', 'array'],
        ]);

        $Y = SchoolYearController::available_year();

        $groups = Group::where('school_year_id', $Y->id)
            ->whereIn('headquarters_id', $request->headquarters)
            ->whereIn('study_year_id', $request->study_years)
            ->whereHas('groupStudents')
            ->pluck('id');

        if (!$groups->count()) {
            Notify::fail(__('There are no students in the selected headquarters and years of study.'));
            return back();
        }


        DB::beginTransaction();

        try {

            $voting = Voting::create([
                'school_year_id' => $Y->id,
                'title' => $request->name,
                'status' => 1, // CREATED
                'created_user_id' => auth()->id(),
                'created_rol' => UserController::myModelIs()
            ]);

            $candidates = [];
            foreach ($request->candidates as $candidate) {

                array_push(
                    $candidates,
                    [
                        'id' => Str::uuid()->toString(),
                        'voting_id' => $voting->id,
                        'student_id' => $candidate
                    ]
                );
            }

            VotingCandidate::insert($candidates);


            $votingGroups = [];
            foreach ($groups as $group) {

                array_push(
                    $votingGroups,
                    [
                        'id' => Str::uuid()->toString(),
                        'voting_id' => $voting->id,
                        'group_id' => $group
                    ]
                );
            }

            VotingGroup::insert($votingGroups);
        } catch (\Throwable $th) {

            DB::rollBack();
            Notify::fail(__('An error has occurred'));
            return back();
        }

        DB::commit();

        Notify::success(__('Voting created!'));
        return redirect()->route('voting.index');
    }

    public function start(Request $request)
    {
        $request->validate([
            'voting' => ['required', Rule::exists('voting', 'id')->where('status', 1)]
        ]);

        $voting = Voting::find($request->voting);
        $voting->update([
            'status' => 2
        ]);

        Notify::success(__(':VOTING voting started!', ['VOTING' => $voting->title]));
        return back();
    }

    public function finish(Voting $voting)
    {
        if ($voting->status->isStarted()) {

            $voting->update([
                'status' => 3
            ]);

            Notify::success(__(':VOTING voting finished!', ['VOTING' => $voting->title]));
            return back();
        }
    }

    public function report(Voting $voting)
    {
        $totalVotes = VotingStudent::where('voting_id', $voting->id)->count();

        $blankVotes = VotingStudent::where('voting_id', $voting->id)->where('voted_for', 'BLANK')->count();

        return view('logro.voting.report', [
            'voting' => $voting,
            'totalVotes' => $totalVotes,
            'blankVotes' => $blankVotes
        ]);
    }

    public function addUser(Request $request)
    {
        $request->validate([
            'voting_role' => ['required', 'in:TEACHER'],
            'voting_user' => ['required']
        ]);

        $model = null;

        if ('TEACHER' === $request->voting_role) {

            $model = Teacher::where('uuid', $request->voting_user)->first();
        }


        if (!is_null($model)) {
            $model->user->assignRole(self::VOTING_COORDINATOR);
            Notify::success(__('He is now voting coordinator'));
        }

        return back();
    }

    public function removeUser(Request $request)
    {
        $request->validate([
            'voting_role' => ['required', 'in:TEACHER'],
            'voting_user' => ['required']
        ]);

        $model = null;

        if ('TEACHER' === $request->voting_role) {

            $model = Teacher::where('uuid', $request->voting_user)->first();
        }


        if (!is_null($model)) {
            $model->user->removeRole(self::VOTING_COORDINATOR);
            Notify::success(__('No longer voting coordinator'));
        }

        return back();
    }
}
