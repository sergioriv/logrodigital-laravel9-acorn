<?php

namespace App\Models;

use App\Traits\FormatDate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResourceSubject extends Model
{
    use HasFactory;
    use FormatDate;

    protected $fillable = [
        'name'
    ];


    /*
     * CHILDREN
     */
    public function subjects()
    {
        return $this->hasMany(Subject::class, 'resource_subject_id');
    }
}
