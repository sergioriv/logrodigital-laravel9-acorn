<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\StudentFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;

class StudentFileController extends Controller
{
    public function update(Student $student, Request $request)
    {

        $request->validate([
            'file_type' => ['required',Rule::exists('student_file_types','id')],
            'file_upload' => ['required','file','mimes:jpg,jpeg,png,webp']
        ]);

        $path_file = $this->upload_file($request, $student->id);

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

            return redirect()->back()->with(
                ['notify' => 'success', 'title' => __('File upload!')],
            );
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

            return redirect()->back()->with(
                ['notify' => 'success', 'title' => __('File updated!')],
            );
        }
    }

    public static function upload_file($request, $student_id)
    {
        if ( $request->hasFile('file_upload') )
        {
            $path = $request->file('file_upload')->store('student_files/'.$student_id, 'public');
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

        return redirect()->back()->with(
            ['notify' => 'success', 'title' => __('Files updated!')],
        );
    }
}
