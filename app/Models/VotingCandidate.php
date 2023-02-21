<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class VotingCandidate extends Model
{
    use Uuid;

    public $timestamps = false;

    protected $fillable = [
        'voting_id',
        'student_id'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class)->select(
            'id',
            'first_name',
            'second_name',
            'first_last_name',
            'second_last_name',
            'group_id')->with('user');
    }

    public function totalVotes()
    {
        return $this->hasMany(VotingStudent::class, 'voted_for', 'id');
    }
}
