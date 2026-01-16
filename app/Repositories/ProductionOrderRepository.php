<?php

namespace App\Repositories;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Collection;

use App\Models\ProductionOrder;

class ProductionOrderRepository 
{
    public function addProductionOrder(array $data): ProductionOrder
    {
        Log::info('DATA RECUE', $data);

        $machineId = $data['machine_id'] ?? null;
        $rawMaterialId = $data['raw_material_id'] ?? null;

        unset($data['machine_id'], $data['raw_material_id']);

        $productionOrder = ProductionOrder::create($data);

        if ($machineId) {
            $productionOrder->machines()->attach($machineId);
        }

        if ($rawMaterialId) {
            $productionOrder->rawMaterials()->attach($rawMaterialId);
        }

        return $productionOrder;
    }

        public function getInfosProductionOrders(): Collection
    {
        $productionOrder = ProductionOrder::select(
            'id',
            'production_order_reference', 
            'theoritical_raw_material_quantity', 
            'start_time', 
            'end_time',
            'theoritical_final_product_quantity',
            'status')
            ->with('rawMaterials:id,name,measurement_unit')
            ->with('machines:id,machine_name')
            ->get();

        return $productionOrder;
    }
}
