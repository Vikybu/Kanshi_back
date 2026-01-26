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
        'machine_id' => 'required|integer|max:255',
        'downtime_reason_id' => 'required|integer|max:255',
        'start_time_downtime' => 'required|date',
        'end_time_downtime' => 'sometimes|date',
        'duration_downtime' => 'sometimes|integer',
    ]);

    // Calcul automatique de la durée si start et end sont présents
    if (!isset($data['duration_downtime']) && isset($data['start_time_downtime'], $data['end_time_downtime'])) {
        $start = \Carbon\Carbon::parse($data['start_time_downtime']);
        $end = \Carbon\Carbon::parse($data['end_time_downtime']);
        $data['duration_downtime'] = $start->diffInMinutes($end); // durée en minutes
    }

    $downtimeReasonMachine = $this->downtimeReasonMachineService->addnewDowntimeReasonMachine($data);

    return response()->json($downtimeReasonMachine, 200);
}

}