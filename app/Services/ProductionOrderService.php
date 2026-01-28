<?php

namespace App\Services;

use App\Models\ProductionOrder;
use App\Repositories\ProductionOrderRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log; 

class ProductionOrderService
{
    protected ProductionOrderRepository $productionOrderRepository;

    public function __construct(ProductionOrderRepository $productionOrderRepository)
    {
        $this->productionOrderRepository = $productionOrderRepository;
    }

    //RÉCUPÉRATION DES OF
    
    public function getAllProductionOrders()
    {
        return $this->productionOrderRepository->getAll();
    }

    public function getAllPlannifiedProductionOrders()
    {
        return $this->productionOrderRepository->getPlannified();
    }

    public function getOneProductionOrder(int $id)
    {
        return $this->productionOrderRepository->getById($id);
    }

    //CRÉATION D'UN OF 
    
    public function addProductionOrder(array $data): ProductionOrder
    {

        $machineId = $data['machine_id'] ?? null;
        $rawMaterialId = $data['raw_material_id'] ?? null;

        unset($data['machine_id'], $data['raw_material_id']);

        $data['real_start_time'] = $data['real_start_time'] ?? null;
        $data['real_end_time'] = $data['real_end_time'] ?? null;
        $data['duration_time'] = $data['duration_time'] ?? null;

        $productionOrder = $this->productionOrderRepository->create($data);

        if ($machineId) {
            $this->productionOrderRepository->attachMachine($productionOrder, $machineId);
        }

        if ($rawMaterialId) {
            $this->productionOrderRepository->attachRawMaterial($productionOrder, $rawMaterialId);
        }

        return $productionOrder;
    }

    //DÉMARRER UN OF POUR UN UTILISATEUR
    
    public function startProductionForUser(int $productionId, int $userId, string $realStartTime, string $status): array
    {
        if ($this->productionOrderRepository->userHasActiveProduction($userId)) {
            throw new \Exception("Vous avez déjà un OF actif. Terminez-le avant d'en ouvrir un nouveau.");
        }

        $productionOrder = $this->productionOrderRepository->findOrFail($productionId);

        $updatedData = [
            'real_start_time' => Carbon::parse($realStartTime)
                ->setTimezone('Europe/Paris')
                ->format('Y-m-d H:i:s'),
            'status' => $status,
        ];

        $productionOrder = $this->productionOrderRepository->update($productionOrder, $updatedData);

        $this->productionOrderRepository->attachUser($productionId, $userId);

        return [
            'success' => true,
            'message' => 'Ordre de production démarré',
            'order' => $productionOrder
        ];
    }

    //TERMINER UN OF POUR UN UTILISATEUR
    
    public function endProductionForUser(int $productionId, int $userId, string $realEndTime, string $status): array
    {
        $this->productionOrderRepository->detachUser($productionId, $userId);

        $productionOrder = $this->productionOrderRepository->findOrFail($productionId);

        $updatedData = [
            'real_end_time' => Carbon::parse($realEndTime)
                ->setTimezone('Europe/Paris')
                ->format('Y-m-d H:i:s'),
            'status' => $status,
        ];

        $productionOrder = $this->productionOrderRepository->update($productionOrder, $updatedData);

        return [
            'success' => true,
            'message' => 'Ordre de production terminé',
            'order' => $productionOrder
        ];
    }

    //METTRE À JOUR QUANTITY_IN_PRODUCTION (AJOUT INCRÉMENTAL)
    
public function updateQuantityInProduction(int $productionOrderId, int $quantityToAdd): ProductionOrder
{

    $productionOrder = $this->productionOrderRepository->findOrFail($productionOrderId);
    $updatedData = [
        'quantity_in_production' => ($productionOrder->quantity_in_production ?? 0) + $quantityToAdd,
    ];

    $result = $this->productionOrderRepository->update($productionOrder, $updatedData);
    return $result;
}

    //ARRÊTER LA PRODUCTION
    
    public function stopProduction(int $productionOrderId, int $finalQuantity): ProductionOrder
    {
        $productionOrder = $this->productionOrderRepository->findOrFail($productionOrderId);

        $updatedData = [
            'actual_final_product_quantity' => $finalQuantity,
            'quantity_in_production' => 0,
            'status' => 'finished',
            'real_end_time' => Carbon::now()->setTimezone('Europe/Paris')->format('Y-m-d H:i:s'),
        ];

        return $this->productionOrderRepository->update($productionOrder, $updatedData);
    }

    //CALCUL DE SIMULATION D'UN OF
    
    public function calculation(
        int $theoritical_raw_material_quantity,
        int $final_product_quantity_per_product,
        int $machine_theoritical_industrial_pace,
        string $measurement_unit,
        int $machine_id,
        string $start_time
    ): array {
        $start = Carbon::parse($start_time);

        $theoritical_final_product_quantity = intdiv(
            ($theoritical_raw_material_quantity * 1000),
            $final_product_quantity_per_product
        );

        if (str_ends_with($measurement_unit, '/min')) {
            $duration_minutes = $theoritical_final_product_quantity / $machine_theoritical_industrial_pace;
        } elseif (str_ends_with($measurement_unit, '/h')) {
            $duration_minutes = ($theoritical_final_product_quantity / $machine_theoritical_industrial_pace) * 60;
        } else {
            throw new \Exception("Unité de mesure inconnue : {$measurement_unit}");
        }

        $end = $start->copy()->addMinutes($duration_minutes);

        return [
            'theoritical_final_product_quantity' => $theoritical_final_product_quantity,
            'end_time' => $end->format('d/m/Y H:i'),
            'duration_minutes' => round($duration_minutes),
        ];
    }
}