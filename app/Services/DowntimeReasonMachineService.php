<?php

namespace App\Services;

use App\Repositories\DowntimeReasonMachineRepository;
use Carbon\Carbon;

class DowntimeReasonMachineService 
{
    protected $downtimeReasonMachineRepository;

    public function __construct(DowntimeReasonMachineRepository $downtimeReasonMachineRepository)
    {
        $this->downtimeReasonMachineRepository = $downtimeReasonMachineRepository;
    }
    
    public function addnewDowntimeReasonMachine($data)
    {
        // Calcul automatique de duration_downtime si start & end sont présents
        if (!isset($data['duration_downtime']) 
            && isset($data['start_time_downtime'], $data['end_time_downtime'])) {
            
            $start = Carbon::parse($data['start_time_downtime']);
            $end = Carbon::parse($data['end_time_downtime']);
            $data['duration_downtime'] = $start->diffInMinutes($end);
        }

        // Appel à la méthode avec le bon nom
        return $this->downtimeReasonMachineRepository->createNewDowntimeReasonMachine($data);
    }
}