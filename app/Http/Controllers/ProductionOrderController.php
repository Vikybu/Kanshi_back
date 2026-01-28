<?php

namespace App\Http\Controllers;

use App\Services\ProductionOrderService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class ProductionOrderController extends Controller 
{
    protected ProductionOrderService $productionOrderService;

    public function __construct(ProductionOrderService $productionOrderService)
    {
        $this->productionOrderService = $productionOrderService;
    }

    //RÉCUPÉRATION DES OF
    
    public function getInfosAllProductionOrder(): JsonResponse
    {
        $productionOrders = $this->productionOrderService->getAllProductionOrders();
        return response()->json($productionOrders, 200);
    }

    public function getInfosAllPlannifiedProductionOrder(): JsonResponse
    {
        $productionOrders = $this->productionOrderService->getAllPlannifiedProductionOrders();
        return response()->json($productionOrders, 200);
    }

    public function getInfosOneProductionOrder(int $id): JsonResponse
    {
        $productionOrder = $this->productionOrderService->getOneProductionOrder($id);
        return response()->json($productionOrder, 200);
    }

    //CRÉATION D'UN OF
    
    public function addANewProductionOrder(Request $request): JsonResponse
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

    //DÉMARRER UN OF POUR UN UTILISATEUR
    
    public function addRealStartTime(Request $request): JsonResponse
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
            return response()->json(['message' => $e->getMessage()], 409);
        }
    }

    //TERMINER UN OF POUR UN UTILISATEUR
    public function stopProductionOrder(Request $request): JsonResponse
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
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    //METTRE À JOUR QUANTITY_IN_PRODUCTION
    
    public function updateQuantity(Request $request, int $id): JsonResponse
{

    $validated = $request->validate([
        'quantity_to_add' => 'required|integer|min:1',
    ]);

    try {
        $productionOrder = $this->productionOrderService->updateQuantityInProduction(
            $id, 
            $validated['quantity_to_add']
        );

        return response()->json([
            'message' => 'Quantité mise à jour avec succès',
            'production_order' => $productionOrder,
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Erreur lors de la mise à jour de la quantité',
            'error' => $e->getMessage(),
        ], 500);
    }
}

    //ARRÊTER LA PRODUCTION
    
    public function stopProduction(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'final_quantity' => 'required|integer|min:0',
        ]);

        try {
            $productionOrder = $this->productionOrderService->stopProduction(
                $id, 
                $validated['final_quantity']
            );

            return response()->json([
                'message' => 'Production arrêtée avec succès',
                'production_order' => $productionOrder,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de l\'arrêt de la production',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    //VÉRIFICATION DE CONFLITS
    
    public function checkConflict(Request $request): JsonResponse
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