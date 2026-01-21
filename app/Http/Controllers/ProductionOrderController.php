<?php

namespace App\Http\Controllers;

use App\Models\ProductionOrder;
use App\Services\ProductionOrderService;
use Illuminate\Http\Request;

class ProductionOrderController extends Controller 
{
    protected $productionOrderService;

     public function __construct(ProductionOrderService $productionOrderService)
    {
        $this->productionOrderService = $productionOrderService;
    }

        public function getInfosAllProductionOrder()
    {
        $productionOrder = $this->productionOrderService->getAllProductionOrders();
        return response()->json($productionOrder, 200);
    }

    public function getInfosOneProductionOrder(int $id)
    {
        $productionOrder = $this->productionOrderService->getOneProductionOrder($id);
        return response()->json($productionOrder, 200);
    }

        public function addRealStartTime(Request $request)
    {
        $realStartTime = $request->input('real_start_time');
        $id = $request->input('id');
        $status = $request->input('status');
        $message = $this->productionOrderService->addInfoRealStartTime($realStartTime, $id, $status);
        return response()->json($message, 201);
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
            'final_products_id' => 'required|integer',
            'raw_material_id' => 'required|exists:raw_materials,id',
            'real_start_time' => 'nullable|date',
            'real_end_time' => 'nullable|date',
        ]);

        $message = $this->productionOrderService->addProductionOrder($productionOrder);

        return response()->json($message, 201);
    }

    public function checkConflict(Request $request)
    {
        $request->validate([
            'machine_id' => 'required|integer',
            'start_time' => 'required|date',
            'end_time' => 'required|date',
        ]);

        $conflict = ProductionOrder::whereHas('machines', function ($q) use ($request) {
            $q->where('machines.id', $request->machine_id);
        })
        ->where(function ($q) use ($request) {
            $q->whereBetween('start_time', [$request->start_time, $request->end_time])
            ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
            ->orWhere(function ($q) use ($request) {
                $q->where('start_time', '<=', $request->start_time)
                    ->where('end_time', '>=', $request->end_time);
            });
        })
        ->exists();

        return response()->json(['conflict' => $conflict]);
    }
}