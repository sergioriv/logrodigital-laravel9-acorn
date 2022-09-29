<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentRemovalCode extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $hidden = [
        'student_id',
        'code',
        'user_id',
        'created_at'
    ];

    public function addMinutes()
    {
        return Carbon::parse($this->created_at)->addMinutes(5);
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
