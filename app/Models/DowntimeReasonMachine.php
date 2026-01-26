<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DowntimeReasonMachine extends Model
{
        protected $fillable = [
        'machine_id',
        'downtime_reason_id',
        'start_time_downtime',
        'end_time_downtime',
        'duration_downtime',
    ];
}