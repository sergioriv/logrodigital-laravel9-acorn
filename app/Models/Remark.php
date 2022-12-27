<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Remark extends Model
{
    use Uuid;

    protected $fillable = [
        'group_id',
        'period_id',
        'student_id',
        'remark'
    ];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }
    public function period()
    {
        return $this->belongsTo(Period::class);
    }
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
