<?php

namespace App\Http\Controllers;

use App\Services\ProductionOrderService;
use App\Models\FinalProduct;
use App\Models\Machine;
use Illuminate\Http\Request;

class CalculationController extends Controller
{

    public function calculation(Request $request,
    ProductionOrderService $service)
    {
        $resultat = $service->calculation(
            theoritical_raw_material_quantity: $request->theoritical_raw_material_quantity,
            machine_theoritical_industrial_pace: $request->machine_theoritical_industrial_pace,
            quantity_per_final_product: $request->quantity_per_final_product,
            start_time: $request->start_time
        );

        return response()->json($resultat);
    }
}