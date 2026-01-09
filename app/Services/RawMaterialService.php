<?php

namespace App\Services;

use App\Models\RawMaterial;
use App\Repositories\RawMaterialRepository;

class RawMaterialService 
{
    protected $rawMaterialRepository;

    public function __construct(RawMaterialRepository $rawMaterialRepository)
    {
        $this->rawMaterialRepository = $rawMaterialRepository;
    }

    public function getListRawMaterial()
    {
        return $this->rawMaterialRepository->getInfosRawMaterial();
    }
}