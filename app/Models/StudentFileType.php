<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentFileType extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function studentFile()
    {
        return $this->hasOne(StudentFile::class,'student_file_type_id');
    }
}
