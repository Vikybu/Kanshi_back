<?php

namespace App\Services;

use App\Repositories\FinalProductRepository;

class FinalProductService 
{
    protected $finalProductRepository;

    public function __construct(FinalProductRepository $finalProductRepository)
    {
        $this->finalProductRepository = $finalProductRepository;
    }

    public function getListFinalProduct()
    {
        return $this->finalProductRepository->getInfosFinalProduct();
    }
}