<?php

namespace App\Repositories;
use Illuminate\Database\Eloquent\Collection;

use App\Models\FinalProduct;

class FinalProductRepository
{
    public function getInfosFinalProduct(): Collection
    {
        $finalProduct = FinalProduct::select(
            'id',
            'name', 
            'reference',
            'quantity_of_product')->get();
        return $finalProduct;
    }
}