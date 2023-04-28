<?php

namespace App\Http\Controllers\support;

use App\Models\Student;

class GenerateStudentCode {

    public static function code()
    {
        $k = "";
        for ($i=0; $i < 6; $i++) {
            $k .= random_int(0, 9);
        }
        $k = date('y') . $k;

        if ( Student::where('code', $k)->count('id') ) {
            GenerateStudentCode::code();
        }

        return $k;
    }

}
