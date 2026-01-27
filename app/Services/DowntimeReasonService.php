<?php

namespace App\Services;

use App\Repositories\DowntimeReasonRepository;

class DowntimeReasonService 
{
    protected $downtimeReasonRepository;

    public function __construct(DowntimeReasonRepository $downtimeReasonRepository)
    {
        $this->downtimeReasonRepository = $downtimeReasonRepository;
    }

    public function getListTypeDowntimeReason()
    {
        return $this->downtimeReasonRepository->getAllTypeDowntimeReason();
    }

    public function getDowntimeReason($type)
    {
        return $this->downtimeReasonRepository->getAllDowntimeReason($type);
    }
}