<?php

namespace App\Repositories;

use App\Models\DowntimeReasonMachine;
use Carbon\Carbon;

class DowntimeReasonMachineRepository
{
    public function createNewDowntimeReasonMachine(array $data): DowntimeReasonMachine
    {
        return DowntimeReasonMachine::create($data);
    }

    public function findDowntimeReasonMachine(int $id): ?DowntimeReasonMachine
    {
        return DowntimeReasonMachine::find($id);
    }

    public function saveDowntimeReasonMachine(DowntimeReasonMachine $downtime): DowntimeReasonMachine
    {
        $downtime->save();
        return $downtime;
    }

    public function getCurrentDowntimeByMachine(int $machineId): ?DowntimeReasonMachine
    {
        return DowntimeReasonMachine::with('downtimeReason')
            ->where('machine_id', $machineId)
            ->whereNull('end_time_downtime')
            ->first();
    }
}
