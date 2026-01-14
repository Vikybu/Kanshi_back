<?php

namespace App\Http\Controllers;

use App\Services\ProductionOrderService;
use Illuminate\Http\Request;

class ProductionOrderController extends Controller 
{
    protected $productionOrderService;

     public function __construct(ProductionOrderService $productionOrderService)
    {
        $this->productionOrderService = $productionOrderService;
    }

    public function addANewProductionOrder(Request $request){

        $productionOrder = $request->validate([
            'production_order_reference' => 'required|string',
            'theoritical_raw_material_quantity' => 'required|integer',
            'actual_raw_material_quantity' => 'required|integer',
            'start_time' => 'required|date',
            'end_time' => 'required|date',
            'theoritical_final_product_quantity'=> 'required|integer',
            'actual_final_product_quantity' => 'required|integer',
            'status' => 'required|string',
            'machine_id' => 'required|exists:machines,id',
        ]);

        $message = $this->productionOrderService->addProductionOrder($productionOrder);

        return response()->json($message, 201);
    }
}