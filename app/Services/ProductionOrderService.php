<?php

namespace App\Services;

use App\Models\ProductionOrder;
use Carbon\Carbon;

class ProductionOrderService
{
    public function calculation(
        int $theoritical_raw_material_quantity,
        int $quantity_per_final_product,
        int $machine_theoritical_industrial_pace, 
        int $machine_id,
        string $start_time
    ): array {
        $start = Carbon::parse($start_time);

        // 1️⃣ Quantité produite
        $theoritical_final_product_quantity = intdiv(
            $theoritical_raw_material_quantity,
            $quantity_per_final_product
        );

        // 2️⃣ Durée de production
        $duration_production = $theoritical_final_product_quantity / $machine_theoritical_industrial_pace;
        $end = $start->copy()->addMinutes($duration_production * 60);

        // 3️⃣ Détection de conflit
        $conflict = ProductionOrder::where('machine_id', $machine_id)
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
            'end_time' => $end->format('Y-m-d H:i'),
            'duree_minutes' => round($duration_production * 60),
            'conflit_planning' => $conflict,
        ];
    }
}