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
            final_product_quantity_per_product: $request->final_product_quantity_per_product,
            measurement_unit: $request->measurement_unit,
            machine_id: $request->machine_id,
            start_time: $request->start_time
        );

        return response()->json($resultat);
    }
}