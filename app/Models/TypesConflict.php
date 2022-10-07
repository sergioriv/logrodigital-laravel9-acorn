<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypesConflict extends Model
{
    use HasFactory;

    protected $table = 'types_conflict';
    public $timestamps = false;

    protected $fillable = ['name'];
}
