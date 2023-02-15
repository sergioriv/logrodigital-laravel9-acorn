<?php

namespace App\Http\Controllers;

use App\Http\Controllers\support\Notify;
use Illuminate\Support\Facades\File;
use ZipArchive;

class ZipController extends Controller
{
    private $path;

    public function __construct(
        $path = null
    )
    {
        $this->path = $path;
    }

    public function downloadGradesGroup($group)
    {
        if ($this->path && $group) {

            $zip = new ZipArchive;
            $path = 'app/reports/' . $this->path;
            $pathZip = public_path('app/reports/Reporte de notas '. $group .' - '. time() .'.zip');

            if ($zip->open($pathZip, ZipArchive::CREATE) === TRUE) {

                $files = File::files(public_path($path));

                if (count($files)) {

                    foreach ($files as $file) {

                        if (!$file->isDir())
                        $zip->addFile($file, basename($file));
                    }

                    $zip->close();

                }
                File::deleteDirectory(public_path($path));

                if (count($files))
                    return response()->download($pathZip)->deleteFileAfterSend();

            }
        }

        Notify::fail(__('An error has occurred'));
        return back();

    }

    public function downloadTeacherGuideGroups($teacherName)
    {
        if ($this->path && $teacherName) {

            $zip = new ZipArchive;
            $path = 'app/reports/' . $this->path;
            $pathZip = public_path('app/reports/Planillas - '. $teacherName .' - '. time() .'.zip');

            $zip->open($pathZip, ZipArchive::CREATE);
            // if ($zip->open($pathZip, ZipArchive::CREATE) === TRUE) {

                $files = File::files(public_path($path));

                if (count($files)) {

                    foreach ($files as $file) {

                        if (!$file->isDir())
                        $zip->addFile($file, basename($file));
                    }

                    $zip->close();

                }
                File::deleteDirectory(public_path($path));

                if (count($files))
                    return response()->download($pathZip)->deleteFileAfterSend();

            // }
        }

        // Notify::fail(__('An error has occurred'));
        // return back();

    }
}
