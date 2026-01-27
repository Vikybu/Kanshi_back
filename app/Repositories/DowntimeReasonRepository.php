<?php

namespace App\Repositories;
use Illuminate\Database\Eloquent\Collection;

use App\Models\DowntimeReason;

class DowntimeReasonRepository
{
    public function getAllTypeDowntimeReason(): Collection
    {
        $type = DowntimeReason::select(
            'type',)->get();
        return $type;
    }

    public function getAllDowntimeReason($type): Collection
    {
        $downtimeReason = DowntimeReason::select(
            'id',
            'name',)
            ->where('type', $type)
            ->get();
            
        return $downtimeReason;
    }
}