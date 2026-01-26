<?php

namespace App\Repositories;
use Illuminate\Database\Eloquent\Collection;

use App\Models\DowntimeReasonMachine;

class DowntimeReasonMachineRepository
{
    public function createNewDowntimeReasonMachine($data): DowntimeReasonMachine
    {
        return DowntimeReasonMachine::create($data);
    }
}