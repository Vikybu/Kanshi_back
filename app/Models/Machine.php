<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Machine extends Model
{
      
    protected $fillable = [
        'machine_name',
        'short_name',
        'theoritical_industrial_pace',
        'measurement_unit',
        'max_capacity',
        'status',
    ];

    public function productionOrders()
    {
        return $this->belongsToMany(ProductionOrder::class);
    }

}
