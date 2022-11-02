<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentReportBook extends Model
{
    use HasFactory;
    use Uuid;

    protected $fillable = [
        'creation_user_id',
        'student_id',
        'resource_study_year_id',
        'url',
        'url_absolute',
        'checked',
        'approval_user_id',
        'approval_date'
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s',
        'checked' => 'boolean'
    ];

    public function creationUser()
    {
        return $this->belongsTo(user::class);
    }
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
    public function resourceStudyYear()
    {
        return $this->belongsTo(ResourceStudyYear::class);
    }
    public function approvalUser()
    {
        return $this->belongsTo(User::class);
    }
}
