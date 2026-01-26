<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DowntimeReason extends Model
{
    protected $table = 'downtimes_reasons';
      
    protected $fillable = [
        'name',
        'type',
    ];

        public function productionOrders()
    {
        return $this->belongsToMany(ProductionOrder::class);
    }

            public function machines()
    {
        return $this->belongsToMany(Machine::class);
    }

}