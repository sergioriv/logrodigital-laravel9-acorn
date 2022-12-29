<?php

namespace App\Models;

use App\Traits\FormatDate;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class StudentDescriptor extends Model
{
    use Uuid;
    use FormatDate;

    public $fillable = [
        'teacher_subject_group_id',
        'student_id',
        'descriptor_id'
    ];

    public function descriptor()
    {
        return $this->belongsTo(Descriptor::class);
    }

}
