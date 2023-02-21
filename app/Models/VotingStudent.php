<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class VotingStudent extends Model
{
    use Uuid;

    public $timestamps = false;

    protected $fillable = [
        'voting_id',
        'student_id',
        'voted_for'
    ];

    public function voting()
    {
        return $this->belongsTo(Voting::class);
    }
}
