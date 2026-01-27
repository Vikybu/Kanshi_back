<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DowntimeReasonProductionOrder extends Model
{
        protected $fillable = [
        'production_order_id',
        'downtime_reason_id',
        'start_time_downtime',
        'end_time_downtime',
        'duration_downtime',
    ];
}