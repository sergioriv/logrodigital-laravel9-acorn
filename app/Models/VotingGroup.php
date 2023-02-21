<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class VotingGroup extends Model
{
    use Uuid;

    public $timestamps = false;

    protected $fillable = [
        'voting_id',
        'group_id'
    ];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}
