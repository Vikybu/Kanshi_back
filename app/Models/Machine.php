<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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

        public function downtimesReasons(): BelongsToMany
    {
        return $this->belongsToMany(
            DowntimeReason::class, 
            'production_order_machine', 
            'production_order_id', 
            'machine_id');
    }

}
