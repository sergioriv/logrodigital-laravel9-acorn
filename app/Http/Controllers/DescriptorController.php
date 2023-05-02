<?php

namespace App\Http\Controllers;

use App\Http\Controllers\support\Notify;
use App\Imports\DescriptorImport;
use App\Models\Descriptor;
use App\Models\ResourceStudyYear;
use App\Models\ResourceSubject;
use App\Models\StudyYear;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class DescriptorController extends Controller
{
    public function index(ResourceSubject $subject, StudyYear $studyYear = null)
    {
        $descriptors = Descriptor::where('resource_subject_id', $subject->id)
            ->when($studyYear, function ($query, $ST) {
                $query->where('resource_study_year_id', $ST->resource_study_year_id);
            }, function ($query) {
                $query->with('resourceStudyYear');
            })->orderBy('resource_study_year_id')->get();

        return view('logro.resource.subject.descriptors.index', [
            'studyYear' => $studyYear,
            'subject' => $subject,
            'descriptors' => $descriptors
        ]);
    }


    public function create(ResourceSubject $subject, StudyYear $studyYear = null)
    {
        return view('logro.resource.subject.descriptors.create', [
            'subject' => $subject,
            'studyYears' => $studyYear ?: ResourceStudyYear::all()
        ]);
    }


    public function store(Request $request, ResourceSubject $subject, StudyYear $studyYear = null)
    {
        $studyYear
        ? $request->validate([
                'period' => ['required', 'in:1,2,3,4,5,6'],
                'inclusive' => ['nullable', 'boolean'],
                'content' => ['required', 'string', 'max:1000']
            ])
        : $request->validate([
                'study_year' => ['required', Rule::exists('resource_study_years', 'uuid')],
                'period' => ['required', 'in:1,2,3,4,5,6'],
                'inclusive' => ['nullable', 'boolean'],
                'content' => ['required', 'string', 'max:1000']
            ]);

        try {

            $resource = ResourceStudyYear::query()
                ->when($studyYear, function ($query, $ST) {
                    $query->whereId($ST->resource_study_year_id);
                }, function ($query) use ($request) {
                    $query->where('uuid', $request->study_year);
                })->first();

            Descriptor::create([
                'resource_study_year_id' => $resource->id,
                'resource_subject_id' => $subject->id,
                'period' => $request->period,
                'inclusive' => $request->inclusive,
                'content' => $request->content
            ]);
        } catch (\Throwable $th) {
            Notify::fail(__('saving error'));
            return back();
        }

        Notify::success(__('Descriptor saved!'));
        return $studyYear
        ? redirect()->route('teacher.subject.descriptors', [$subject, $studyYear])
        : redirect()->route('subject.descriptors', $subject);
    }

    public function import_view(ResourceSubject $subject)
    {
        return view('logro.resource.subject.descriptors.import', [
            'subject' => $subject,
            'studyYears' => ResourceStudyYear::all()
        ]);
    }

    public function import_store(Request $request, ResourceSubject $subject)
    {
        $request->validate([
            'study_year' => ['required', Rule::exists('resource_study_years', 'uuid')],
            'file' => ['required', 'file', 'max:5000', 'mimes:xls,xlsx']
        ]);

        try {
            $resource = ResourceStudyYear::select('id')->where('uuid', $request->study_year)->first();

            Excel::import(new DescriptorImport($subject->id, $resource->id), $request->file('file'));
        } catch (\Throwable $th) {
            Notify::fail(__('saving error') . ': ' . $th->getMessage());
            return back();
        }

        Notify::success(__('Loaded Excel!'));
        return redirect()->back();
    }
}
