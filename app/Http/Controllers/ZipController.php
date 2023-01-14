<?php

namespace App\Http\Controllers;

use App\Http\Controllers\support\Notify;
use Illuminate\Support\Facades\File;
use ZipArchive;

class ZipController extends Controller
{
    private $path;
    private $group;

    public function __construct(
        $path = null,
        $group = null
    )
    {
        $this->path = $path;
        $this->group = $group;
    }

    public function downloadGradesGroup()
    {
        if ($this->path && $this->group) {

            $zip = new ZipArchive;
            $path = 'app/reports/' . $this->path;
            $pathZip = public_path('app/reports/Reporte de notas '. $this->group .' - '. time() .'.zip');

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
}
