<?php

namespace App\Repositories;
use Illuminate\Support\Facades\Log;

use App\Models\ProductionOrder;

class ProductionOrderRepository 
{
    public function addProductionOrder(array $data): ProductionOrder
    {
        Log::info('DATA RECUE', $data);
        $machineId = $data['machine_id'];
        $productionOrder = ProductionOrder::create($data);
        $productionOrder->machines()->attach($machineId);
        return $productionOrder;
    }
}