<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class SentEmail extends Model
{
    use Uuid;

    protected $fillable = [
        'subject',
        'message',
        'sentTo',
        'created_user_type',
        'created_user_id'
    ];

    protected $casts = ['sentTo' => 'array'];

    public function created_user()
    {
        return $this->morphTo('created_user', 'created_user_type', 'created_user_id', 'id');
    }
}
