<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinalProduct extends Model
{
      
    protected $fillable = [
        'name',
        'type',
        'theoritical_industrial_pace',
        'active',
    ];

}