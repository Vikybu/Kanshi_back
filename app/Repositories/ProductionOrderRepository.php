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

        $data['real_start_time'] = $data['real_start_time'] ?? null;
        $data['real_end_time'] = $data['real_end_time'] ?? null;
        $data['duration_time'] = $data['duration_time'] ?? null;

        $productionOrder = ProductionOrder::create($data);

        if ($machineId) {
            $productionOrder->machines()->attach($machineId);
        }

        if ($rawMaterialId) {
            $productionOrder->rawMaterials()->attach($rawMaterialId);
        }

        return $productionOrder;
    }

    public function modifyFORealStartTime($realStartTime, $id, $status)
    {
        $order = ProductionOrder::find($id);
        if (!$order){
            return ['error' => 'Ordre de production introuvable'];
        }

        $order->real_start_time = $realStartTime;
        $order->status = $status;
        $order->save();

        return[
        'success' => true,
        'message' => 'Date de démarrage enregistrée',
        'order' => $order
        ];
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

    public function getInfosOneProductionOrders($id): ?ProductionOrder
    {
        $productionOrder = ProductionOrder::select(
            'id',
            'real_start_time',
            'theoritical_raw_material_quantity', 
            'production_order_reference', 
            'start_time', 
            'actual_final_product_quantity',
            'theoritical_final_product_quantity',
            'status',
            'duration_time'
        )
        ->with('rawMaterials:id,name,measurement_unit')
        ->with('machines:id,machine_name,theoritical_industrial_pace')
        ->where('id', $id)
        ->first();

        return $productionOrder;
    }

    public function updateQuantityProduction($id, $actual_final_product_quantity)
    {
        $order = ProductionOrder::find($id);
        if (!$order){
            return ['error' => 'Ordre de production introuvable'];
        }

        $order->actual_final_product_quantity += $actual_final_product_quantity;
        $order->save();

        return[
        'success' => true,
        'message' => 'Quantité enregistrée',
        'order' => $order
        ];
    }
}
