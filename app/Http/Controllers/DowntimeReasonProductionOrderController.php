<?php

namespace App\Http\Controllers;

use App\Services\DowntimeReasonProductionOrderService;
use Illuminate\Http\Request;

class DowntimeReasonProductionOrderController extends Controller
{
    protected $downtimeReasonProductionOrderService;

     public function __construct(DowntimeReasonProductionOrderService $downtimeReasonProductionOrderService)
    {
        $this->downtimeReasonProductionOrderService = $downtimeReasonProductionOrderService;
    }

    public function addDowntimeReasonProductionOrder(Request $request)
    {
        $data = $request->validate([
            'production_order_id' => 'required|string|max:255',
            'downtime_reason_id' => 'required|string|max:255',
            'start_time_downtime' => 'required|datetime',
            'end_time_downtime' => 'sometimes|datetime',
            'duration_downtime' => 'sometimes|integer',
        ]);

        $downtimeReasonProductionOrderService = $this->downtimeReasonProductionOrderService->addnewDowntimeReasonProductionOrder($data);

        return response()->json($downtimeReasonProductionOrderService, 200);
    }

}