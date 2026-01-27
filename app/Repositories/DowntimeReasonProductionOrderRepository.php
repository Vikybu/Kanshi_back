<?php

namespace App\Repositories;
use Illuminate\Database\Eloquent\Collection;

use App\Models\DowntimeReasonProductionOrder;

class DowntimeReasonProductionOrderRepository
{
    public function createNewDonwntimeReasonProductionOrder($data): Collection
    {
        return DowntimeReasonProductionOrder::create($data);
    }
}