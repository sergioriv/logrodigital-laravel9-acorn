<?php

namespace App\Models;

use App\Http\Controllers\SchoolYearController;
use App\Traits\FormatDate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupStudentRetired extends Model
{
    use HasFactory;
    use FormatDate;

    protected $table = 'group_students_retired';

    protected $fillable = [
        'group_id',
        'student_id'
    ];

    /*
    * PARENTS
    */
    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function groupPrimary()
    {
        return $this->belongsTo(Group::class, 'group_id', 'id')
                    ->where('school_year_id', SchoolYearController::current_year()->id)
                    ->whereNull('specialty');
    }
    public function groupSpecialty()
    {
        return $this->belongsTo(Group::class, 'group_id', 'id')
                    ->where('school_year_id', SchoolYearController::current_year()->id)
                    ->where('specialty', 1);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
