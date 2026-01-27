<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProductionOrder extends Model
{
        protected $fillable = [
        'production_order_id',
        'user_id',
        'active',
    ];
}