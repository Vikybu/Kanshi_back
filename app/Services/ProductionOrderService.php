<?php

namespace App\Services;

use App\Models\ProductionOrder;
use Carbon\Carbon;

class ProductionOrderService
{
    /**
     * Calcul de la simulation d'un ordre de fabrication
     *
     * @param int $theoritical_raw_material_quantity Quantité de matière première
     * @param int $final_product_quantity_per_product Quantité de matière par produit final
     * @param int $machine_theoritical_industrial_pace Cadence théorique de la machine
     * @param string $measurement_unit Unité de la cadence ('barquettes/min' ou 'barquettes/h')
     * @param int $machine_id ID de la machine
     * @param string $start_time Heure de début (Y-m-d H:i)
     *
     * @return array
     */
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

        // Vérification de conflit de planning
        $conflict = ProductionOrder::whereHas('machines', function ($q) use ($machine_id) {
                $q->where('machines.id', $machine_id);
            })
            ->where(function ($q) use ($start, $end) {
                $q->whereBetween('start_time', [$start, $end])
                  ->orWhereBetween('end_time', [$start, $end])
                  ->orWhere(function ($q) use ($start, $end) {
                      $q->where('start_time', '<=', $start)
                        ->where('end_time', '>=', $end);
                  });
            })
            ->exists();

        return [
            'theoritical_final_product_quantity' => $theoritical_final_product_quantity,
            'end_time' => $end->format('d/m/Y H:i'),
            'duration_minutes' => round($duration_minutes),
            'conflit_planning' => $conflict,
        ];
    }
}