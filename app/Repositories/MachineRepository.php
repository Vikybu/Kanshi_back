<?php

namespace App\Repositories;
use Illuminate\Database\Eloquent\Collection;

use App\Models\Machine;

class MachineRepository 
{
    public function addMachine(array $data): Machine
    {
        return Machine::create($data);
    }

    public function getInfosMachines(): Collection
    {
        $machine = Machine::select(
            'id',
            'machine_name', 
            'short_name', 
            'theoritical_industrial_pace', 
            'measurement_unit')->get();
        return $machine;
    }
}