<?php

namespace App\Features\Process\ReachTruck\Observers;

use App\Features\Masters\Warehouses\Domains\Models\Warehouse;
use App\Features\Process\ReachTruck\Domains\Models\ReachTruck;
use App\Features\Process\PalletManagement\Domains\Models\Pallet;

class ReachTruckObserver
{
    /**
     * Handle the reach truck "created" event.
     *
     * @param  \App\Features\Process\ReachTruck\Domains\Models\ReachTruck  $reachTruck
     * @return void
     */
    public function created(ReachTruck $reachTruck)
    {
        //
    }

    /**
     * Handle the reach truck "updating" event.
     *
     * @param  \App\Features\Process\ReachTruck\Domains\Models\ReachTruck  $reachTruck
     * @return void
     */
    public function updating(ReachTruck $reachTruck)
    {
        //
    }

    /**
     * Handle the reach truck "updated" event.
     *
     * @param  \App\Features\Process\ReachTruck\Domains\Models\ReachTruck  $reachTruck
     * @return void
     */
    public function updated(ReachTruck $reachTruck)
    {
        if ($reachTruck->isDirty('to_locationable_id')) {
            if ($reachTruck->to_locationable_type == Warehouse::class && $reachTruck->to_locationable_type != Warehouse::GENERAL_ID) {
                if (!empty($reachTruck->getOriginal('to_locationable_id'))) {
                    $oldWarehouse = Warehouse::find($reachTruck->getOriginal('to_locationable_id'));
                    $oldWarehouse->updateIsEmpty(true);
                }
            }

            $reachTruck->refresh();
            if ($reachTruck->to_locationable_type == Warehouse::class && $reachTruck->to_locationable_id != Warehouse::GENERAL_ID) {
                $reachTruck->toLocationable->updateIsEmpty(false);
            }

            if ($reachTruck->is_transfered) {
                $locationableType = $reachTruck->to_locationable_type;
                $locationableId = $reachTruck->to_locationable_id;
            } else {
                $locationableType = $reachTruck->from_locationable_type;
                $locationableId = $reachTruck->from_locationable_id;
            }

            $reachTruck->pallet->masterPallet->updateLastLocationable($locationableType, $locationableId);
        }
    }

    /**
     * Handle the reach truck "deleted" event.
     *
     * @param  \App\Features\Process\ReachTruck\Domains\Models\ReachTruck  $reachTruck
     * @return void
     */
    public function deleted(ReachTruck $reachTruck)
    {
        //
    }

    /**
     * Handle the reach truck "restored" event.
     *
     * @param  \App\Features\Process\ReachTruck\Domains\Models\ReachTruck  $reachTruck
     * @return void
     */
    public function restored(ReachTruck $reachTruck)
    {
        //
    }

    /**
     * Handle the reach truck "force deleted" event.
     *
     * @param  \App\Features\Process\ReachTruck\Domains\Models\ReachTruck  $reachTruck
     * @return void
     */
    public function forceDeleted(ReachTruck $reachTruck)
    {
        //
    }

    public function createPalletLocationData(ReachTruck $reachTruck)
    {
        return [
            'pallet_id' => $reachTruck->pallet_id,
            'locationable_type' => $reachTruck->to_locationable_type,
            'locationable_id' => $reachTruck->to_locationable_id,
            'created_by' => $reachTruck->transfered_by
        ];
    }
}
