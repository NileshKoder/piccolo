<?php

namespace App\Features\Process\ReachTruck\Actions;

use App\Features\Masters\Warehouses\Domains\Models\Warehouse;
use App\Features\Process\PalletManagement\Domains\Models\Pallet;
use App\Features\Process\ReachTruck\Domains\Models\ReachTruck;

class ReachTruckAction
{
    public function createReachTruckFromPallet(Pallet $pallet): ?ReachTruck
    {
        $reachTruckData = $this->prepareReachTruckData($pallet);
        return ReachTruck::persistReachTruck($reachTruckData, $pallet);
    }

    public function prepareReachTruckData(Pallet $pallet): array
    {
        $reachTruckData = [];
        $reachTruckData['from_locationable_type'] = $pallet->currentPalletLocation->locationable_type;
        $reachTruckData['from_locationable_id'] = $pallet->currentPalletLocation->locationable_id;
        $reachTruckData['to_locationable_type'] = Warehouse::class;
        $reachTruckData['to_locationable_id'] = null;
        $reachTruckData['is_transfered'] = false;
        $reachTruckData['created_by'] = auth()->user()->id;

        return $reachTruckData;
    }
}
