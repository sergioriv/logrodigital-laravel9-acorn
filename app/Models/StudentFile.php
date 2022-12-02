<?php

namespace App\Models;

use App\Traits\FormatDate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentFile extends Model
{
    use HasFactory;
    use FormatDate;

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

    public function studentFileType()
    {
        return $this->belongsTo(StudentFileType::class);
    }
}
