<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinalProduct extends Model
{
      
    protected $fillable = [
        'name',
        'type',
        'quantity_of_product',
        'measurement_unit',
        'reference',
        'theoritical_industrial_pace',
        'active',
    ];

}