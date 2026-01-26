<?php

namespace App\Services;

use App\Repositories\DowntimeReasonProductionOrderRepository;

class DowntimeReasonProductionOrderService 
{
    protected $downtimeReasonProductionOrderRepository;

    public function __construct(DowntimeReasonProductionOrderRepository $downtimeReasonProductionOrderRepository)
    {
        $this->downtimeReasonProductionOrderRepository = $downtimeReasonProductionOrderRepository;
    }
    
    public function addnewDowntimeReasonProductionOrder($data)
    {
        return $this->downtimeReasonProductionOrderRepository->createNewDonwntimeReasonProductionOrder($data);
    }
}