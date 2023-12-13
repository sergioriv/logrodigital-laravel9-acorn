<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Secretariat extends Model
{
    use HasFactory;
    use Uuid;

    protected $table = 'user_secretariat';
    protected $primaryKey = 'uuid';


    protected $fillable = [
        'id',
        'name',
        'last_names',
        'email',
        'telephone',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id');
    }

    public function getFullName()
    {
        return "{$this->name} {$this->last_names}";
    }
}
