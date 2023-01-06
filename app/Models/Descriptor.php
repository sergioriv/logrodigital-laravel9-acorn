<?php

namespace App\Models;

use App\Traits\FormatDate;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class Descriptor extends Model
{
    use Uuid;
    use FormatDate;

    public $fillable = [
        'resource_study_year_id',
        'resource_subject_id',
        'inclusive',
        'content'
    ];

    protected $casts = ['inclusive' => 'boolean'];


    public function resourceStudyYear()
    {
        return $this->belongsTo(ResourceStudyYear::class);
    }
    public function resourceSubject()
    {
        return $this->belongsTo(ResourceSubject::class);
    }
}
