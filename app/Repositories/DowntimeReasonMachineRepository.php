<?php

namespace App\Repositories;
use Illuminate\Database\Eloquent\Collection;

use App\Models\DowntimeReasonMachine;

class DowntimeReasonMachineRepository
{
    public function createNewDonwntimeReasonMachine($data): Collection
    {
        return DowntimeReasonMachine::create($data);
    }
}