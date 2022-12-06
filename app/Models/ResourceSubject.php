<?php

namespace App\Models;

use App\Traits\FormatDate;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResourceSubject extends Model
{
    use HasFactory;
    use Uuid;
    use FormatDate;

    protected $fillable = [
        'name',
        'public_name',
        'specialty'
    ];

    private function isSpecialty()
    {
        if ($this->specialty)
            return ' <i class="icon bi-star-fill"></i> ';

        return null;
    }

    public function name(): Attribute
    {
        $isSpecialty = $this->isSpecialty();
        return Attribute::make(
            get: fn ($v) => $isSpecialty . $v
        );
    }


    /*
     * CHILDREN
     */
    public function subjects()
    {
        return $this->hasMany(Subject::class, 'resource_subject_id');
    }
}
