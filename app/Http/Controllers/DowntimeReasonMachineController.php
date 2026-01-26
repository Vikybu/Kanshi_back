<?php

namespace App\Http\Controllers;

use App\Services\DowntimeReasonMachineService;
use Illuminate\Http\Request;

class DowntimeReasonMachineController extends Controller
{
    protected $downtimeReasonMachineService;

     public function __construct(DowntimeReasonMachineService $downtimeReasonMachineService)
    {
        $this->downtimeReasonMachineService = $downtimeReasonMachineService;
    }

    public function addDowntimeReasonMachine(Request $request)
    {
        $data = $request->validate([
            'machine_id' => 'required|string|max:255',
            'downtime_reason_id' => 'required|string|max:255',
            'start_time_downtime' => 'required|datetime',
            'end_time_downtime' => 'sometimes|datetime',
            'duration_downtime' => 'sometimes|integer',
        ]);

        $downtimeReasonMachine = $this->downtimeReasonMachineService->addnewDowntimeReasonMachine($data);

        return response()->json($downtimeReasonMachine, 200);
    }

}