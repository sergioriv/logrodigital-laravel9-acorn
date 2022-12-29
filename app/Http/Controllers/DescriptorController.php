<?php

namespace App\Http\Controllers;

use App\Http\Controllers\support\Notify;
use App\Models\Descriptor;
use App\Models\ResourceStudyYear;
use App\Models\ResourceSubject;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DescriptorController extends Controller
{
    public function index(ResourceSubject $subject)
    {
        $descriptors = Descriptor::where('resource_subject_id', $subject->id)
                ->with('resourceStudyYear')
                ->orderBy('resource_study_year_id')
                ->get();

        return view('logro.resource.subject.descriptors.index', [
            'subject' => $subject,
            'descriptors' => $descriptors
        ]);
    }


    public function create(ResourceSubject $subject)
    {
        $resourceStudyYear = ResourceStudyYear::all();
        return view('logro.resource.subject.descriptors.create', [
            'subject' => $subject,
            'studyYears' => $resourceStudyYear
        ]);
    }


    public function store(Request $request, ResourceSubject $subject)
    {
        $request->validate([
            'study_year' => ['required', Rule::exists('resource_study_years', 'uuid')],
            'content' => ['required', 'string', 'max:200'],
            'inclusive' => ['nullable', 'boolean']
        ]);

        try {

            $resource = ResourceStudyYear::where('uuid', $request->study_year)->first();


            Descriptor::create([
                'resource_study_year_id' => $resource->id,
                'resource_subject_id' => $subject->id,
                'inclusive' => $request->inclusive,
                'content' => $request->content
            ]);
        } catch (\Throwable $th) {
            Notify::fail(__('saving error'));
            return back();
        }

        Notify::success(__('Descriptor saved!'));
        return redirect()->route('subject.descriptors', $subject);
    }
}
