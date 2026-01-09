<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RawMaterial extends Model
{
      
    protected $fillable = [
        'name',
        'reference',
        'type',
        'measurement_unit',
        'theoritical_industrial_pace',
        'active',
    ];

}