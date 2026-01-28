<?php

namespace App\Http\Controllers;

use App\Services\DowntimeReasonMachineService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class DowntimeReasonMachineController extends Controller
{
    protected $downtimeReasonMachineService;

    public function __construct(DowntimeReasonMachineService $downtimeReasonMachineService)
    {
        $this->downtimeReasonMachineService = $downtimeReasonMachineService;
    }

    public function addDowntimeReasonMachine(Request $request): JsonResponse
    {
        $data = $request->validate([
            'machine_id' => 'required|integer',
            'downtime_reason_id' => 'required|integer',
            'start_time_downtime' => 'required|date',
            'end_time_downtime' => 'sometimes|date',
            'duration_downtime' => 'sometimes|integer',
        ]);

        $downtimeReasonMachine = $this->downtimeReasonMachineService->addNewDowntimeReasonMachine($data);

        return response()->json($downtimeReasonMachine, 201);
    }

    public function updateEndDowntimeReasonMachine(Request $request, int $id): JsonResponse
    {
        $data = $request->validate([
            'end_time_downtime' => 'required|date',
        ]);

        $downtimeReasonMachine = $this->downtimeReasonMachineService
            ->updateEndTimeDowntimeReasonMachine($id, $data['end_time_downtime']);

        return response()->json($downtimeReasonMachine, 200);
    }

    public function getCurrentDowntimeByMachine(int $machineId)
    {
        $downtime = $this->downtimeReasonMachineService
            ->getCurrentDowntimeByMachine($machineId);

        return response()->json($downtime);
    }

}
