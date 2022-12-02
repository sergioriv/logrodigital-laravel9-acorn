<?php

namespace App\Models;

use App\Traits\FormatDate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeriodType extends Model
{
    use HasFactory;
    use FormatDate;

    public $timestamps = false;

    protected $fillable = [
        'name'
    ];


    /*
     * CHILDREN
     */
    public function periods()
    {
        return $this->hasMany(Period::class);
    }

}
