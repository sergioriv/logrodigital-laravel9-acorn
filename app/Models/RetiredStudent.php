<?php

namespace App\Models;

use App\Http\Controllers\support\UserController;
use App\Traits\FormatDate;
use Illuminate\Database\Eloquent\Model;

class RetiredStudent extends Model
{
    use FormatDate;

    protected $fillable = [
        'student_id',
        'created_rol',
        'created_user_id',
        'created_rol'
    ];

    public function creatorName() { return $this->created_rol::where('id', $this->created_user_id)->first()->getFullName(); }
    public function student() { return $this->belongsTo(Student::class); }

    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $model->created_rol = UserController::myModelIs();
            $model->created_user_id = auth()->id();
        });
    }
}
