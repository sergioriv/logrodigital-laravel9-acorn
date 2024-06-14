<?php

namespace App\Models;

use App\Traits\FormatDate;
use App\Traits\Uuid;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Rector extends Model
{
    use Uuid;
    use FormatDate;

    protected $primaryKey = 'uuid';
    protected $table = 'user_rector';

    protected $fillable = [
        'id',
        'names',
        'last_names',
        'institutional_email',
        'date_entry',

        'document',
        'expedition_city',
        'birth_city',
        'birthdate',
        'residence_city',
        'address',
        'telephone',
        'cellphone',
        'marital_status',

        'type_appointment',
        'type_admin_act',
        'appointment_number',
        'date_appointment',
        'file_appointment',
        'possession_certificate',
        'date_possession_certificate',
        'file_possession_certificate',
        'transfer_resolution',
        'date_transfer_resolution',
        'file_transfer_resolution',

        'active'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id');
    }
    public function expeditionCity()
    {
        return $this->belongsTo(City::class, 'expedition_city', 'id');
    }
    public function birthCity()
    {
        return $this->belongsTo(City::class, 'birth_city', 'id');
    }
    public function residenceCity()
    {
        return $this->belongsTo(City::class, 'residence_city', 'id');
    }


    /*
     *
     * Children
     *
     *  */
    public function hierarchies()
    {
        return $this->hasMany(RectorHierarchy::class, 'rector_id', 'id');
    }

    public function degrees()
    {
        return $this->hasMany(RectorDegree::class, 'rector_id', 'id');
    }

    public function employments()
    {
        return $this->hasMany(RectorEmploymentHistory::class, 'rector_id', 'id');
    }

    /* accesored */
    public function getFullName()
    {
        return "{$this->names} {$this->last_names}";
    }

    /* Cast */
    protected function birthdate(): Attribute
    {
        return Attribute::make(
            set: fn($v) => Carbon::parse($v)->format('Y-m-d')
        );
    }
    protected function dateEntry(): Attribute
    {
        return Attribute::make(
            set: fn($v) => Carbon::parse($v)->format('Y-m-d')
        );
    }
}
