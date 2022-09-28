<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SecurityCode extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $hidden = [
        'email',
        'code',
        'user_id',
        'created_at'
    ];

    public function addMinutes()
    {
        return Carbon::parse($this->created_at)->addMinutes(5);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
