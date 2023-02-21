<?php

namespace App\Models;

use App\Enums\VotingStatusEnum;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class Voting extends Model
{
    use Uuid;

    protected $table = "voting";

    protected $fillable = [
        'school_year_id',
        'title',
        'status',
        'created_user_id',
        'created_rol'
    ];

    protected $casts = [
        'status' => VotingStatusEnum::class
    ];

    public function creatorName()
    {
        return $this->created_rol::where('id', $this->created_user_id)->first()->getFullName();
    }

    public function candidates()
    {
        return $this->hasMany(VotingCandidate::class, 'voting_id', 'id');
    }
    public function constituencies()
    {
        return $this->hasMany(VotingGroup::class);
    }
    public function voteStudents()
    {
        return $this->hasMany(VotingStudent::class);
    }

}
