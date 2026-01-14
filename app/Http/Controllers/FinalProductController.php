<?php

namespace App\Http\Controllers;

use App\Services\FinalProductService;

class FinalProductController extends Controller
{
    protected $finalProductService;

     public function __construct(FinalProductService $finalProductService)
    {
        $this->finalProductService = $finalProductService;
    }

    public function getAllFinalProduct()
    {
        $finalProduct = $this->finalProductService->getListFinalProduct();
        return response()->json($finalProduct, 200);
    }
}