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

    /* ===== Création d'un OF ===== */
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

    /* ===== Vérifier si un utilisateur a déjà un OF actif ===== */
    public function userHasActiveProduction(int $userId): bool
    {
        return DB::table($this->pivotTable)
            ->where('user_id', $userId)
            ->where('active_production', 1)
            ->exists();
    }

    /* ===== Démarrer un OF pour un utilisateur ===== */
    public function startProductionForUser(int $productionId, int $userId, string $realStartTime, string $status)
    {
        // Vérification OF actif
        if ($this->userHasActiveProduction($userId)) {
            throw new \Exception("Vous avez déjà un OF actif. Terminez-le avant d'en ouvrir un nouveau.");
        }

        // Mettre à jour production_orders
        $order = ProductionOrder::find($productionId);
        if (!$order) {
            throw new \Exception("Ordre de production introuvable");
        }

        $order->real_start_time = Carbon::parse($realStartTime)
            ->setTimezone('Europe/Paris')
            ->format('Y-m-d H:i:s');
        $order->status = $status;
        $order->save();

        // Créer ou activer la ligne dans production_order_user
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
            DB::table($this->pivotTable)
                ->insert([
                    'production_order_id' => $productionId,
                    'user_id' => $userId,
                    'active_production' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
        }

        return [
            'success' => true,
            'message' => 'Ordre de production démarré',
            'order' => $order
        ];
    }

    /* ===== Terminer un OF pour un utilisateur ===== */
    public function endProductionForUser(int $productionId, int $userId, string $realEndTime, string $status)
    {
        // Désactiver la ligne dans production_order_user
        DB::table($this->pivotTable)
            ->where('production_order_id', $productionId)
            ->where('user_id', $userId)
            ->update([
                'active_production' => 0,
                'updated_at' => now()
            ]);

        // Mettre à jour production_orders
        $order = ProductionOrder::find($productionId);
        if (!$order) {
            throw new \Exception("Ordre de production introuvable");
        }

        $order->real_end_time = Carbon::parse($realEndTime)
            ->setTimezone('Europe/Paris')
            ->format('Y-m-d H:i:s');
        $order->status = $status;
        $order->save();

        return [
            'success' => true,
            'message' => 'Ordre de production terminé',
            'order' => $order
        ];
    }

    /* ===== Modifier real_start_time et status (sans utilisateur) ===== */
    public function modifyFORealStartTime($realStartTime, $id, $status)
    {
        $order = ProductionOrder::find($id);
        if (!$order){
            return ['error' => 'Ordre de production introuvable'];
        }

        $order->real_start_time = $realStartTime;
        $order->status = $status;
        $order->save();

        return [
            'success' => true,
            'message' => 'Date de démarrage enregistrée',
            'order' => $order
        ];
    }

    /* ===== Modifier real_end_time et status (sans utilisateur) ===== */
    public function updateEndTimeProductionOrder($id, $real_end_time, $status)
    {
        $order = ProductionOrder::find($id);
        if (!$order){
            return ['error' => 'Ordre de production introuvable'];
        }

        $order->real_end_time = $real_end_time;
        $order->status = $status;
        $order->save();

        return [
            'success' => true,
            'message' => 'Fin de l ordre de fabrication',
            'order' => $order
        ];
    }

    /* ===== Mise à jour quantité production ===== */
    public function updateQuantityProduction($id, $actual_final_product_quantity)
    {
        $order = ProductionOrder::find($id);
        if (!$order){
            return ['error' => 'Ordre de production introuvable'];
        }

        $order->actual_final_product_quantity += $actual_final_product_quantity;
        $order->save();

        return [
            'success' => true,
            'message' => 'Quantité enregistrée',
            'order' => $order
        ];
    }

    /* ===== Récupérer tous les OF ===== */
    public function getInfosProductionOrders(): Collection
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

    /* ===== Récupérer OF planifiés ===== */
    public function getInfosPlannifiedProductionOrders(): Collection
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

    /* ===== Récupérer un OF précis ===== */
    public function getInfosOneProductionOrders($id): ?ProductionOrder
    {
        return ProductionOrder::select(
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
    }
}
