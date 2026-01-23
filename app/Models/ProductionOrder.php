<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ProductionOrder extends Model
{
      
    protected $fillable = [
        'production_order_reference',
        'theoritical_raw_material_quantity',
        'actual_raw_material_quantity',
        'start_time',
        'end_time',
        'time_measurement',
        'theoritical_final_product_quantity',
        'actual_final_product_quantity',
        'status',
        'final_products_id',
        'real_start_time',
        'real_end_time',
        'duration_time'
    ];

    public function machines(): BelongsToMany
    {
        return $this->belongsToMany(
            Machine::class,
            'machine_production_order',
            'production_order_id',
            'machine_id'
        );
    }

    public function rawMaterials()
    {
        return $this->belongsToMany(
            RawMaterial::class,
            'production_order_raw_material',
            'production_order_id',
            'raw_material_id'
        );
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'production_order_user', 'production_order_id', 'user_id')
                    ->withPivot('active_production');
    }
}