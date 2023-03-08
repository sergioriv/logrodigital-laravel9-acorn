<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ChangeEmailCode extends Model
{
    public $timestamps = false;

    protected $hidden = [
        'model_type',
        'model_id',
        'code',
        'created_id',
        'created_at'
    ];

    public function addMinutes()
    {
        return Carbon::parse($this->created_at)->addMinutes(5);
    }

    public function model()
    {
        return $this->morphTo('model', 'model_type', 'model_id', 'id');
    }
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_id');
    }
}
