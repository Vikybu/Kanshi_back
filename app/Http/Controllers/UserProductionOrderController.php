<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductionOrder;

class UserProductionOrderController extends Controller
{

    public function getActiveProductionOrder(Request $request)
    {
        $userId = $request->input('user_id');

        if (!$userId) {
            return response()->json(['active_production' => false, 'error' => 'user_id manquant'], 400);
        }

        $activeOrder = ProductionOrder::whereHas('users', function ($query) use ($userId) {
            $query->where('user_id', $userId)
                ->where('active_production', true);
        })->first();

        if (!$activeOrder) {
            return response()->json(['active_production' => false]);
        }

        return response()->json([
            'active_production' => true,
            'production_order_id' => $activeOrder->id
        ]);
    }
}