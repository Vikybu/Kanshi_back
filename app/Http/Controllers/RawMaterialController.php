<?php

namespace App\Http\Controllers;

use App\Services\RawMaterialService;

class RawMaterialController extends Controller
{
    protected $rawMaterialService;

     public function __construct(RawMaterialService $rawMaterialService)
    {
        $this->rawMaterialService = $rawMaterialService;
    }

    public function getAllRawMaterial()
    {
        $rawMaterial = $this->rawMaterialService->getListRawMaterial();
        return response()->json($rawMaterial, 200);
    }
}