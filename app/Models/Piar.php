<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Piar extends CastCreateModel
{
    use HasFactory;

    protected $table = 'piar';

    protected $fillable = [
        'student_id',
        'subject_id',
        'annotation',
        'user_id'
    ];


    /* Parents */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
