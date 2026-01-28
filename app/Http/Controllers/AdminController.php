<?php

namespace App\Http\Controllers;

use App\Models\Machine;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log; 

class AdminController extends Controller
{
    public function getAllMachines(): JsonResponse
{
    $machines = Machine::with(['currentOf', 'currentDowntime'])->get()->map(function ($machine) {

        $currentOf = $machine->currentOf?->first();
        $currentDowntime = $machine->currentDowntime?->first();

        if ($currentDowntime) {
            $status = 'stopped';
        } elseif ($currentOf) {
            $status = 'inProduction';
        } else {
            $status = 'idle';
        }

        return [
            'id' => $machine->id,
            'machine_name' => $machine->machine_name,
            'status' => $status,
            'current_of' => $currentOf ? [
                'id' => $currentOf->id,
                'production_order_reference' => $currentOf->production_order_reference,
                'actual_final_product_quantity' => $currentOf->actual_final_product_quantity,
                'theoritical_final_product_quantity' => $currentOf->theoritical_final_product_quantity,
                'quantity_in_production' => $currentOf->quantity_in_production,
            ] : null,
            'current_qty' => $currentOf?->quantity_in_production ?? 0,
            'qty_to_produce' => $currentOf?->theoritical_final_product_quantity ?? 0,
        ];
    });
    
    return response()->json($machines);
}
}