<?php

namespace App\Http\Controllers;

use App\Services\DowntimeReasonService;

class DowntimeReasonController extends Controller
{
    protected $downtimeReasonService;

     public function __construct(DowntimeReasonService $downtimeReasonService)
    {
        $this->downtimeReasonService = $downtimeReasonService;
    }

    public function getTypeDowntimeReason()
    {
        $typeList = $this->downtimeReasonService->getListTypeDowntimeReason();
        return response()->json($typeList, 200);
    }

        public function getDowntimeReason(string $type)
    {
        $downtimeReasonList = $this->downtimeReasonService->getDowntimeReason($type);
        return response()->json($downtimeReasonList, 200);
    }
}