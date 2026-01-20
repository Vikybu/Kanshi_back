<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class UserProductionOrder extends Model
{
        protected $fillable = [
        'production_order_id',
        'user_id',
        'active',
    ];
}