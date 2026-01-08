<?php

namespace App\Services;

use App\Models\Machine;
use App\Repositories\MachineRepository;

class MachineService 
{
    protected $machineRepository;

    public function __construct(MachineRepository $machineRepository)
    {
        $this->machineRepository = $machineRepository;
    }

    public function createMachine(array $data):Machine
    {
        $data['short_name'] ??= '';
        $data['status'] ??= 'stop';
        $data['measurement_unit'] ??= 'pcs';

        return $this->machineRepository->addMachine($data);
    }

    public function getAllMachines()
    {
        return $this->machineRepository->getInfosMachines();
    }
}