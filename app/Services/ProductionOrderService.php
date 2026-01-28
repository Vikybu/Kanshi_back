<?php

namespace App\Services;

use App\Models\ProductionOrder;
use App\Repositories\ProductionOrderRepository;
use Carbon\Carbon;

class ProductionOrderService
{
    protected $productionOrderRepository;

    public function __construct(ProductionOrderRepository $productionOrderRepository)
    {
        $this->productionOrderRepository = $productionOrderRepository;
    }

    /* ===== Récupération OF ===== */
    public function getAllProductionOrders()
    {
        return $this->productionOrderRepository->getInfosProductionOrders();
    }

    public function getAllPlannifiedProductionOrders()
    {
        return $this->productionOrderRepository->getInfosPlannifiedProductionOrders();
    }

    public function getOneProductionOrder($id)
    {
        return $this->productionOrderRepository->getInfosOneProductionOrders($id);
    }

    /* ===== Quantité / fin de production ===== */
    public function addQuantityProduction($id, $actual_final_product_quantity)
    {
        return $this->productionOrderRepository->updateQuantityProduction($id, $actual_final_product_quantity);
    }

    public function endOfTheProductionOrder($id, $real_end_time, $status)
    {
        return $this->productionOrderRepository->updateEndTimeProductionOrder($id, $real_end_time, $status);
    }

    public function addInfoRealStartTime(string $realStartTime, int $id, string $status)
    {
        return $this->productionOrderRepository->modifyFORealStartTime($realStartTime, $id, $status);
    }

    public function addProductionOrder(array $data)
    {
        return $this->productionOrderRepository->addProductionOrder($data);
    }

    /* ===== Démarrer un OF pour un utilisateur ===== */
    public function startProductionForUser(int $productionId, int $userId, string $realStartTime, string $status)
    {
        return $this->productionOrderRepository->startProductionForUser($productionId, $userId, $realStartTime, $status);
    }

    /* ===== Terminer un OF pour un utilisateur ===== */
    public function endProductionForUser(int $productionId, int $userId, string $realEndTime, string $status)
    {
        return $this->productionOrderRepository->endProductionForUser($productionId, $userId, $realEndTime, $status);
    }

    /* Calcul de simulation d'un OF */
    public function calculation(
        int $theoritical_raw_material_quantity,
        int $final_product_quantity_per_product,
        int $machine_theoritical_industrial_pace,
        string $measurement_unit,
        int $machine_id,
        string $start_time
    ): array {
        $start = Carbon::parse($start_time);

        // Calcul de la quantité finale produite
        $theoritical_final_product_quantity = intdiv(
            ($theoritical_raw_material_quantity * 1000),
            $final_product_quantity_per_product
        );

        // Calcul de la durée de production en minutes selon l'unité
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
