<?php

namespace App\Http\Controllers;

use App\Http\Controllers\support\Notify;
use App\Models\Student;
use App\Models\StudentFile;
use App\Models\StudentReportBook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;

class StudentReportBookController extends Controller
{

    const FILE = 'file_reportbook';

    function __construct()
    {
        $this->middleware('can:students.documents.edit');
        $this->middleware('can:students.documents.checked')->only('checked');
    }

    public function update(Student $student, Request $request)
    {
        $request->validate([
            'reportbook' => ['required', Rule::exists('resource_study_years', 'id')],
            self::FILE => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048']
        ]);


        $reportBook = StudentReportBook::where('student_id', $student->id)
            ->where('resource_study_year_id', $request->reportbook)
            ->first();

        if ($reportBook === NULL) {

            $reportBook = new StudentReportBook();
            $reportBook->creation_user_id = Auth::user()->id;
            $reportBook->student_id = $student->id;
            $reportBook->resource_study_year_id = $request->reportbook;

        } else {

            $reportBook->creation_user_id = Auth::user()->id;

        }

        $path_file = $this->upload_file($request, $student->id);
        if (!$path_file) {
            return redirect()->back()->withErrors(__('An error occurred while uploading the file, please try again.'));
        }

        if ($request->hasFile(self::FILE) && $reportBook->url_absolute !== NULL) {
            File::delete(public_path($reportBook->url_absolute));
        }

        $reportBook->url = config('app.url') .'/'. $path_file;
        $reportBook->url_absolute = $path_file;
        $reportBook->save();


        Notify::success(__('File upload!'));
        return redirect()->back();
    }

    private static function upload_file($request, $student_id)
    {
        if ($request->hasFile(self::FILE)) {
            $path = $request->file(self::FILE)->store('students/' . $student_id . '/files', 'public');
            return config('filesystems.disks.public.url') . '/' . $path;
        } else return null;
    }

    public function checked(Request $request, Student $student)
    {
        if (!empty($request->student_files)) {
            $files = StudentFile::where('student_id', $student->id)
                ->where('checked', null)
                ->orWhere('checked', 0)->get();

            foreach ($files as $file) :
                if (in_array($file->id, $request->student_files)) {
                    StudentFile::find($file->id)->update([
                        'checked' => TRUE,
                        'approval_user_id' => Auth::user()->id,
                        'approval_date' => now()
                    ]);
                } else {
                    StudentFile::find($file->id)->update([
                        'checked' => FALSE,
                        'approval_user_id' => NULL,
                        'approval_date' => NULL
                    ]);
                }
            endforeach;
        } else {
            $files = StudentFile::where('student_id', $student->id)
                ->where('checked', null)
                ->orWhere('checked', 0)->update(['checked' => FALSE]);
        }

        Notify::success(__('Files updated!'));
        return redirect()->back();
    }
}
