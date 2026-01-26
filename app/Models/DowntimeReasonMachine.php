<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DowntimeReasonMachine extends Model
{
    use HasFactory;

    protected $table = 'downtimes_reasons_machines';

        protected $fillable = [
        'machine_id',
        'downtime_reason_id',
        'start_time_downtime',
        'end_time_downtime',
        'duration_downtime',
    ];
}