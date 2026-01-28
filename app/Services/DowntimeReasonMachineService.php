<?php

namespace App\Services;

use App\Repositories\DowntimeReasonMachineRepository;
use App\Models\Machine;
use App\Models\ProductionOrder;
use Carbon\Carbon;
use Exception;

class DowntimeReasonMachineService 
{
    protected $downtimeReasonMachineRepository;

    public function __construct(DowntimeReasonMachineRepository $downtimeReasonMachineRepository)
    {
        $this->downtimeReasonMachineRepository = $downtimeReasonMachineRepository;
    }

    /** Démarrer un nouvel arrêt */
    public function addNewDowntimeReasonMachine(array $data)
    {
        // Vérifier s'il y a déjà un arrêt en cours pour cette machine
        $current = $this->downtimeReasonMachineRepository->getCurrentDowntimeByMachine($data['machine_id']);
        if ($current) {
            throw new Exception("Un arrêt est déjà en cours sur cette machine.");
        }

        // Calcul automatique de duration_downtime si start & end sont présents
        if (!isset($data['duration_downtime']) 
            && isset($data['start_time_downtime'], $data['end_time_downtime'])) {
            
            $start = Carbon::parse($data['start_time_downtime']);
            $end = Carbon::parse($data['end_time_downtime']);
            $data['duration_downtime'] = $start->diffInMinutes($end);
        }

        // Création de l'arrêt
        $downtime = $this->downtimeReasonMachineRepository->createNewDowntimeReasonMachine($data);

        // Si pas de fin, mettre la machine (et l'OF) en statut "stopped"
        if (!isset($data['end_time_downtime']) || !$data['end_time_downtime']) {
            $machine = Machine::find($data['machine_id']);
            if ($machine) {
                $machine->status = 'stopped';
                $machine->save();
            }

            // Optionnel : si tu veux mettre aussi l'OF en arrêt
            if (isset($data['of_id'])) { 
                $of = ProductionOrder::find($data['of_id']);
                if ($of) {
                    $of->status = 'stopped';
                    $of->save();
                }
            }
        }

        return $downtime;
    }

    /** Terminer un arrêt existant */
    public function updateEndTimeDowntimeReasonMachine(int $id, string $endTime)
    {
        $downtime = $this->downtimeReasonMachineRepository->findDowntimeReasonMachine($id);

        if (!$downtime) {
            throw new Exception("Arrêt introuvable.");
        }

        if ($downtime->end_time_downtime !== null) {
            throw new Exception("Cet arrêt est déjà terminé.");
        }

        $start = Carbon::parse($downtime->start_time_downtime);
        $end = Carbon::parse($endTime);

        if ($end->lessThan($start)) {
            throw new Exception("La fin ne peut pas être avant le début.");
        }

        $downtime->end_time_downtime = $endTime;
        $downtime->duration_downtime = $start->diffInMinutes($end);

        $downtime = $this->downtimeReasonMachineRepository->saveDowntimeReasonMachine($downtime);

        // Machine et OF redeviendront "inProduction" après fin arrêt
        $machine = Machine::find($downtime->machine_id);
        if ($machine) {
            $machine->status = 'inProduction';
            $machine->save();
        }

        if ($downtime->of_id ?? false) {
            $of = productionOrder::find($downtime->of_id);
            if ($of) {
                $of->status = 'inProduction';
                $of->save();
            }
        }

        return $downtime;
    }

    public function getCurrentDowntimeByMachine(int $machineId)
    {
        return $this->downtimeReasonMachineRepository->getCurrentDowntimeByMachine($machineId);
    }
}
