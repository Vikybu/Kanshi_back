<?php

namespace App\Repositories;

use App\Models\Machine;

class MachineRepository 
{
    public function addMachine(array $data): Machine
    {
        return Machine::create($data);
    }
}