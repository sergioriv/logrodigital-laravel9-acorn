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

        if ( $reportBook === NULL ) {

            $reportBook = new StudentReportBook();
            $reportBook->student_id = $student->id;
            $reportBook->resource_study_year_id = $request->reportbook;

        }

        $path_file = $this->upload_file($request, $student->id);
        if ( !$path_file ) {
            return redirect()->back()->withErrors(__('An error occurred while uploading the file, please try again.'));
        }

        if ($request->hasFile(self::FILE) && $reportBook->url_absolute !== NULL) {
            File::delete(public_path($reportBook->url_absolute));
        }

        $reportBook->creation_user_id = Auth::id();
        $reportBook->url = config('app.url') .'/'. $path_file;
        $reportBook->url_absolute = $path_file;
        $reportBook->save();


        static::tab();
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

    public function checked(Student $student, Request $request)
    {
        $request->validate([
            'reportbooks_checked' => ['nullable', Rule::exists('student_report_books','id')->where('student_id',$student->id)]
        ]);

        $reportBooks = $student->reportBooks->whereNull('checked');

        foreach ($reportBooks as $book) {
            if ( in_array($book->id, $request->reportbooks_checked ?? []) ) {

                $book->checked = TRUE;
                $book->approval_user_id = Auth::id();
                $book->approval_date = now()->format('Y-m-d');
                $book->save();

            } else {

                $this->delete_reportBook($book);

            }
        }

        static::tab();
        Notify::success(__('Files updated!'));
        return redirect()->back();
    }

    public function delete(Student $student, Request $request)
    {
        $request->validate([
            'studentReportBookDeleteInput' => ['required', Rule::exists('student_report_books', 'id')->where('student_id', $student->id)]
        ]);

        $file = StudentReportBook::find($request->studentReportBookDeleteInput);

        $this->delete_reportBook($file);

        Notify::success(__('report book deleted!'));
        $this->tab();
        return back();
    }

    private function delete_reportBook($book)
    {
        File::delete(public_path($book->url_absolute));

        $book->delete();
    }

    public static function fileDelete($book)
    {
        File::delete(public_path($book->url_absolute));

        $book->delete();
    }

    private static function tab()
    {
        session()->flash('tab', 'reportBook');
    }

}
