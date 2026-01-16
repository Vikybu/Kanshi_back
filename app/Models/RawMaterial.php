<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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

    public function productionOrders(): BelongsToMany
    {
        return $this->belongsToMany(
            ProductionOrder::class,
            'production_order_raw_material',
            'raw_material_id',
            'production_order_id'
        );
    }

}