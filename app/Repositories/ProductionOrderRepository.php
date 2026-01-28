<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Collection;
use App\Models\ProductionOrder;
use Carbon\Carbon;

class ProductionOrderRepository 
{
    protected $pivotTable = 'production_order_user';

    //MÉTHODES GÉNÉRIQUES
    
    public function findOrFail(int $id): ProductionOrder
    {
        return ProductionOrder::findOrFail($id);
    }

public function update(ProductionOrder $productionOrder, array $data): ProductionOrder
{

    $productionOrder->update($data);
    
    $fresh = $productionOrder->fresh();

    return $fresh;
}

    public function create(array $data): ProductionOrder
    {
        return ProductionOrder::create($data);
    }

    //RÉCUPÉRATION DES DONNÉES
    
    public function getAll(): Collection
    {
        return ProductionOrder::select(
            'id',
            'production_order_reference', 
            'theoritical_raw_material_quantity', 
            'start_time', 
            'end_time',
            'theoritical_final_product_quantity',
            'status'
        )
        ->with('rawMaterials:id,name,measurement_unit')
        ->with('machines:id,machine_name')
        ->get();
    }

    public function getPlannified(): Collection
    {
        return ProductionOrder::select(
            'id',
            'production_order_reference', 
            'theoritical_raw_material_quantity', 
            'start_time', 
            'end_time',
            'theoritical_final_product_quantity',
            'status'
        )
        ->with('rawMaterials:id,name,measurement_unit')
        ->with('machines:id,machine_name')
        ->where('status', 'plannified')
        ->get();
    }

    public function getById(int $id): ?ProductionOrder
    {
        return ProductionOrder::select(
            'id',
            'real_start_time',
            'theoritical_raw_material_quantity', 
            'production_order_reference', 
            'start_time', 
            'actual_final_product_quantity',
            'theoritical_final_product_quantity',
            'quantity_in_production',
            'status',
            'duration_time',
            'quantity_in_production'
        )
        ->with('rawMaterials:id,name,measurement_unit')
        ->with('machines:id,machine_name,theoritical_industrial_pace')
        ->where('id', $id)
        ->first();
    }

    //GESTION DES UTILISATEURS
    
    public function userHasActiveProduction(int $userId): bool
    {
        return DB::table($this->pivotTable)
            ->where('user_id', $userId)
            ->where('active_production', 1)
            ->exists();
    }

    public function attachUser(int $productionId, int $userId): void
    {
        $exists = DB::table($this->pivotTable)
            ->where('production_order_id', $productionId)
            ->where('user_id', $userId)
            ->exists();

        if ($exists) {
            DB::table($this->pivotTable)
                ->where('production_order_id', $productionId)
                ->where('user_id', $userId)
                ->update(['active_production' => 1, 'updated_at' => now()]);
        } else {
            DB::table($this->pivotTable)->insert([
                'production_order_id' => $productionId,
                'user_id' => $userId,
                'active_production' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function detachUser(int $productionId, int $userId): void
    {
        DB::table($this->pivotTable)
            ->where('production_order_id', $productionId)
            ->where('user_id', $userId)
            ->update([
                'active_production' => 0,
                'updated_at' => now()
            ]);
    }

    //GESTION DES RELATIONS
    
    public function attachMachine(ProductionOrder $productionOrder, int $machineId): void
    {
        $productionOrder->machines()->attach($machineId);
    }

    public function attachRawMaterial(ProductionOrder $productionOrder, int $rawMaterialId): void
    {
        $productionOrder->rawMaterials()->attach($rawMaterialId);
    }
}