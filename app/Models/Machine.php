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
        return $this->belongsToMany(ProductionOrder::class, 'machine_production_order', 'machine_id', 'production_order_id');
    }

    public function downtimesReasons(): BelongsToMany
    {
        return $this->belongsToMany(
            DowntimeReason::class, 
            'production_order_machine', 
            'production_order_id', 
            'machine_id'
        );
    }

    public function currentOf()
    {
        // OF en cours pour cette machine : hasOne via la table pivot machine_production_order
        return $this->belongsToMany(ProductionOrder::class, 'machine_production_order', 'machine_id', 'production_order_id')
                    ->where('status', 'inProduction')
                    ->withPivot('machine_id', 'production_order_id')
                    ->limit(1); // récupère seulement 1 OF
    }


    public function currentDowntime()
    {
        return $this->hasOne(DowntimeReasonMachine::class, 'machine_id')->whereNull('end_time_downtime');
    }
}

