<?php

namespace App\Repositories;
use Illuminate\Database\Eloquent\Collection;

use App\Models\RawMaterial;

class RawMaterialRepository
{
    public function getInfosRawMaterial(): Collection
    {
        $rawMaterial = RawMaterial::select(
            'id',
            'name', 
            'reference')->get();
        return $rawMaterial;
    }
}