<?php

namespace App\Http\Controllers;

use App\Http\Controllers\support\Notify;
use App\Models\Student;
use App\Models\StudentFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;

class StudentFileController extends Controller
{

    function __construct()
    {
        $this->middleware('can:students.documents.edit');
        $this->middleware('can:students.documents.checked')->only('checked');

    }

    public function update(Student $student, Request $request)
    {

        $request->validate([
            'file_type' => ['required',Rule::exists('student_file_types','id')],
            'file_upload' => ['required','file','mimes:jpg,jpeg,png,webp','max:2048']
        ]);

        $path_file = $this->upload_file($request, 'file_upload', $student->id);

        $student_file = StudentFile::where('student_id', $student->id)
                ->where('student_file_type_id', $request->file_type)
                ->first();

        if ( $student_file === NULL )
        {
            StudentFile::create([
                'student_id' => $student->id,
                'student_file_type_id' => $request->file_type,
                'url' => config('app.url') .'/'. $path_file,
                'url_absolute' => $path_file,
                'checked' => NULL,
                'creation_user_id' => Auth::user()->id
            ]);

            Notify::success(__('File upload!'));
            return redirect()->back();
        } else
        {

            if ( $request->hasFile('file_upload') )
                File::delete(public_path($student_file->url_absolute));

            $renewed = $student_file->approval_date === NULL ? FALSE : TRUE ;
            $student_file->update([
                'url' => config('app.url') .'/'. $path_file,
                'url_absolute' => $path_file,
                'renewed' => $renewed,
                'checked' => NULL,
                'creation_user_id' => Auth::user()->id

            ]);

            Notify::success(__('File updated!'));
            return redirect()->back();
        }
    }

    public static function upload_file($request, $file_name, $student_id)
    {
        if ( $request->hasFile($file_name) )
        {
            $path = $request->file($file_name)->store('students/'.$student_id.'/files', 'public');
            return config('filesystems.disks.public.url') .'/' . $path;
        }
        else return null;
    }

    public function checked(Request $request, Student $student)
    {
        if ( !empty($request->student_files) )
        {
            $files = StudentFile::where('student_id', $student->id)
                    ->where('checked', null)
                    ->orWhere('checked', 0)->get();

            foreach ($files as $file) :
                if ( in_array( $file->id, $request->student_files ) )
                {
                    StudentFile::find($file->id)->update([
                        'checked' => TRUE,
                        'approval_user_id' => Auth::user()->id,
                        'approval_date' => now()
                    ]);
                } else
                {
                    StudentFile::find($file->id)->update([
                        'checked' => FALSE,
                        'approval_user_id' => NULL,
                        'approval_date' => NULL
                    ]);
                }
            endforeach;

        } else
        {
            $files = StudentFile::where('student_id', $student->id)
                    ->where('checked', null)
                    ->orWhere('checked', 0)->update(['checked' => FALSE]);
        }

        Notify::success(__('Files updated!'));
        return redirect()->back();
    }
}
