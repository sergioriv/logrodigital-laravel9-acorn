<?php

namespace App\Http\Controllers;

use App\Http\Controllers\support\Notify;
use App\Http\Middleware\YearCurrentMiddleware;
use App\Models\ResourceArea;
use App\Models\ResourceSubject;
use App\Models\Subject;
use Illuminate\Http\Request;

class SpecialtyController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:subjects.index');
        $this->middleware('can:subjects.edit')->only('store');

        $this->middleware(YearCurrentMiddleware::class)->only('store');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Y = SchoolYearController::current_year();

        if (NULL === $Y->available) {
            $resourceAreas = ResourceArea::where('specialty', 1)->whereHas('subjects', fn ($s) => $s->where('school_year_id', $Y->id))
                ->with(['subjects' => fn ($s) => $s->where('school_year_id', $Y->id)])
                ->orderBy('name')->get();
        } else {
            $resourceAreas = ResourceArea::where('specialty', 1)->with(['subjects' => fn ($s) => $s->where('school_year_id', $Y->id)])
                ->orderBy('name')->get();
        }


        $resourceSubjects = ResourceSubject::where('specialty', 1)->whereNot(function ($query) use ($Y) {
            $query->whereHas('subjects', function ($subject) use ($Y) {
                $subject->where('school_year_id', $Y->id);
            });
        })->orderBy('name')->get();

        return view('logro.subject.specialties')->with([
            'Y' => $Y,
            'resourceAreas' => $resourceAreas,
            'resourceSubjects' => $resourceSubjects
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $Y = SchoolYearController::current_year();

        $resourceAreas = ResourceArea::where('specialty', 1)->get();

        foreach ($resourceAreas as $area) {
            $areaInput = 'area-' . $area->id;
            if ($request->has($areaInput)) {
                foreach ($request->$areaInput as $subject) {

                    /* Para evitar que una relacion sea modificada */
                    $sbCreated = Subject::where('school_year_id', $Y->id)
                        ->where('resource_area_id', $area->id)
                        ->where('resource_subject_id', $subject)
                        ->count();
                    if (!$sbCreated) {

                        /* Verificar que una asignatura no sea de especialidad */
                        $sbArea = ResourceSubject::where('id', $subject)->where('specialty', 1)->first();

                        if (NULL !== $sbArea) {
                            Subject::create([
                                'school_year_id' => $Y->id,
                                'resource_area_id' => $area->id,
                                'resource_subject_id' => $subject
                            ]);
                        }
                    }
                }
            }
        }

        Notify::success(__('Areas & Subjects updated!'));
        return redirect()->route('specialties.index');
    }
}
