<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class ResultSchoolYear extends Model
{
    protected $table = 'result_school_year';
    public $timestamps = false;

    protected $fillable = [
        'school_year_id',
        'student_id',
        'result'
    ];

    protected $casts = ['result' => 'boolean'];

    public function result()
    {
        return match($this->result) {
            true => 'aprobado',
            false => 'reprobado'
        };
    }
}
