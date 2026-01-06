<?php

namespace App\Http\Controllers;

use App\Services\MachineService;
use Illuminate\Http\Request;

class MachineController extends Controller
{
    protected $machineService;

     public function __construct(MachineService $machineService)
    {
        $this->machineService = $machineService;
    }

    public function addAMachine(Request $request)
    {
        $data = $request->validate([
            'machine_name' => 'required|string|max:255',
            'short_name' => 'sometimes|string|max:255',
            'theoritical_industrial_pace' => 'required|integer',
            'measurement_unit' => 'sometimes|string',
            'max_capacity' => 'sometimes|integer',
            'status' => 'sometimes|string',
        ]);

        $machine = $this->machineService->createMachine($data);

        return response()->json($machine, 201);
    }
}