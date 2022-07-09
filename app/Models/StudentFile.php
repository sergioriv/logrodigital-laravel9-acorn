<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class StudentFile extends CastCreateModel
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'student_file_type_id',
        'url',
        'url_absolute',
        'checked',
        'renewed',
        'approval_user_id',
        'approval_date',
        'creation_user_id'
    ];

    /* public function studentFileType()
    {
        return $this->belongsTo(StudentFileType::class);
    } */
}
