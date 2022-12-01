<?php

namespace App\Http\Controllers;

use App\Http\Controllers\support\Notify;
use App\Models\Student;
use App\Models\StudentFile;
use App\Models\StudentFileType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;

class StudentFileController extends Controller
{

    const FILE = 'file_upload';
    const FILE_DISABILITY = 'disability_certificate';

    function __construct()
    {
        $this->middleware('can:students.documents.edit');
        $this->middleware('can:students.documents.checked')->only('checked');
    }

    public function update(Student $student, Request $request)
    {

        $request->validate([
            'file_type' => ['required', Rule::exists('student_file_types', 'id')],
            self::FILE => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048']
        ]);


        $studentFile = StudentFile::where('student_id', $student->id)
            ->where('student_file_type_id', $request->file_type)
            ->first();

        if ($studentFile === NULL) {

            $studentFile = new StudentFile();
            $studentFile->student_id = $student->id;
            $studentFile->student_file_type_id = $request->file_type;
        }

        $path_file = static::upload_file($request, self::FILE, $student->id);
        if (!$path_file) {
            return redirect()->back()->withErrors(__('An error occurred while uploading the file, please try again.'));
        }

        if ($request->hasFile(self::FILE) && $studentFile->url_absolute !== NULL) {
            File::delete(public_path($studentFile->url_absolute));
        }

        $studentFile->creation_user_id = Auth::user()->id;
        $studentFile->url = config('app.url') . '/' . $path_file;
        $studentFile->url_absolute = $path_file;
        $studentFile->save();


        /* Update Student Avatar */
        if ($request->file_type == 9) { // 9 => foto para documento

            $student->user->avatar = $path_file;
            $student->user->save();
        }

        static::tab();
        Notify::success(__('File upload!'));
        return redirect()->back();
    }

    public static function upload_disability_file($request, $student)
    {
        /*
         * En caso de no tener file, la disacapacidad sera null
         *
         *  */

        $path_file = static::upload_file($request, self::FILE_DISABILITY, $student->id);
        if (!$path_file) {
            return false;
        }

        $fileTypeDisability = StudentFileType::select('id')->where('inclusive', 1)->first()->id; //certificado de discapacidad

        $studentDisabilityFile = StudentFile::where('student_id', $student->id)
            ->where('student_file_type_id', $fileTypeDisability)
            ->first();

        if (is_null($studentDisabilityFile)) {

            $studentDisabilityFile = new StudentFile();
            $studentDisabilityFile->student_id = $student->id;
            $studentDisabilityFile->student_file_type_id = $fileTypeDisability;
        }

        /* Se elimina el archivo anterior en caso de existir */
        if ($request->hasFile(self::FILE_DISABILITY) && $studentDisabilityFile->url_absolute !== NULL) {
            File::delete(public_path($studentDisabilityFile->url_absolute));
        }

        /* Actualizamos los valores */
        $studentDisabilityFile->creation_user_id = Auth::user()->id;
        $studentDisabilityFile->url = config('app.url') . '/' . $path_file;
        $studentDisabilityFile->url_absolute = $path_file;
        $studentDisabilityFile->save();

        return true;
    }

    private static function upload_file($request, $file, $student_id)
    {
        if ($request->hasFile($file)) {
            $path = $request->file($file)->store('students/' . $student_id . '/files', 'public');
            return config('filesystems.disks.public.url') . '/' . $path;
        } else return null;
    }

    public function checked(Request $request, Student $student)
    {
        $request->validate([
            'student_files' => ['nullable', Rule::exists('student_files', 'id')->where('student_id', $student->id)]
        ]);

        $studentFiles = $student->files->whereNull('checked');

        foreach ($studentFiles as $file) {
            if (in_array($file->id, $request->student_files ?? [])) {

                $file->checked = TRUE;
                $file->approval_user_id = Auth::user()->id;
                $file->approval_date = now()->format('Y-m-d');
                $file->save();
            } else {

                $this->delete_studentFile($file);

                if ($file->student_file_type_id == 9) { //eliminacion de avatar

                    $student->user->avatar = NULL;
                    $student->user->save();
                }
            }
        }

        static::tab();
        Notify::success(__('Files updated!'));
        return redirect()->back();
    }

    private function delete_studentFile($file)
    {
        File::delete(public_path($file->url_absolute));

        $file->delete();
    }

    public function __checked(Request $request, Student $student)
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

    private static function tab()
    {
        session()->flash('tab', 'documents');
    }
}
