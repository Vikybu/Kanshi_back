<?php

namespace App\Http\Controllers;

use App\Services\ProductionOrderService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ProductionOrderController extends Controller 
{
    protected $productionOrderService;

    public function __construct(ProductionOrderService $productionOrderService)
    {
        $this->productionOrderService = $productionOrderService;
    }

    /* ===== Récupérer tous les OF ===== */
    public function getInfosAllProductionOrder()
    {
        $productionOrders = $this->productionOrderService->getAllProductionOrders();
        return response()->json($productionOrders, 200);
    }

    /* ===== Récupérer tous les OF planifiés ===== */
    public function getInfosAllPlannifiedProductionOrder()
    {
        $productionOrders = $this->productionOrderService->getAllPlannifiedProductionOrders();
        return response()->json($productionOrders, 200);
    }

    /* ===== Démarrer un OF pour un utilisateur ===== */
    public function addRealStartTime(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|integer|exists:production_orders,id',
            'real_start_time' => 'required|date',
            'status' => 'required|string',
            'user_id' => 'required|integer|exists:users,id',
        ]);

        try {
            $result = $this->productionOrderService->startProductionForUser(
                $validated['id'],
                $validated['user_id'],
                $validated['real_start_time'],
                $validated['status']
            );

            return response()->json($result, 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 409);
        }
    }

    /* ===== Terminer un OF pour un utilisateur ===== */
    public function stopProductionOrder(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|integer|exists:production_orders,id',
            'real_end_time' => 'required|date',
            'status' => 'required|string',
            'user_id' => 'required|integer|exists:users,id',
        ]);

        try {
            $result = $this->productionOrderService->endProductionForUser(
                $validated['id'],
                $validated['user_id'],
                $validated['real_end_time'],
                $validated['status']
            );

            return response()->json($result, 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /* ===== Récupérer un OF précis ===== */
    public function getInfosOneProductionOrder(int $id)
    {
        $productionOrder = $this->productionOrderService->getOneProductionOrder($id);
        return response()->json($productionOrder, 200);
    }

    /* ===== Ajouter quantité produite ===== */
    public function addQuantityProduct(Request $request)
    {
        $id = $request->input('id');
        $actual_final_product_quantity = $request->input('actual_final_product_quantity');

        $result = $this->productionOrderService->addQuantityProduction(
            $id,
            $actual_final_product_quantity
        );

        return response()->json($result, 200);
    }

    /* ===== Ajouter un nouvel OF ===== */
    public function addANewProductionOrder(Request $request)
    {
        $validated = $request->validate([
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
            'duration_time' => 'nullable|integer',
        ]);

        $result = $this->productionOrderService->addProductionOrder($validated);

        return response()->json($result, 201);
    }

    /* ===== Vérification conflit d’OF pour une machine ===== */
    public function checkConflict(Request $request)
    {
        $request->validate([
            'machine_id' => 'required|integer',
            'start_time' => 'required|date',
            'end_time' => 'required|date',
        ]);

        $conflict = \App\Models\ProductionOrder::whereHas('machines', function ($q) use ($request) {
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
