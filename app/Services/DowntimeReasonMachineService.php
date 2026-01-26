<?php

namespace App\Services;

use App\Repositories\DowntimeReasonMachineRepository;

class DowntimeReasonMachineService 
{
    protected $downtimeReasonMachineRepository;

    public function __construct(DowntimeReasonMachineRepository $downtimeReasonMachineRepository)
    {
        $this->downtimeReasonMachineRepository = $downtimeReasonMachineRepository;
    }
    
    public function addnewDowntimeReasonMachine($data)
    {
        return $this->downtimeReasonMachineRepository->createNewDonwntimeReasonMachine($data);
    }
}