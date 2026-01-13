<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
        'final_product_id'
    ];

    public function machines()
    {
        return $this->belongsToMany(Machine::class);
    }

}
