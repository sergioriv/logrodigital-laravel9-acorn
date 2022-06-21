<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;


class ResourceSubject extends CastCreateModel
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];


    /*
     * CHILDREN
     */
    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }
}
