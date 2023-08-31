<?php

namespace App\Features\Process\ReachTruck\Observers;

use App\Features\Masters\Locations\Domains\Models\Location;
use App\Features\Masters\Warehouses\Domains\Models\Warehouse;
use App\Features\OrderManagement\Domains\Models\OrderItemPalletDetails;
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
        if($reachTruck->to_locationable_type == Warehouse::class && $reachTruck->is_transfered) {
            $this->updateWarehouseLocationOnPallet($reachTruck);
        }
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

            if ($reachTruck->to_locationable_type == Warehouse::class && $reachTruck->to_locationable_id != Warehouse::GENERAL_ID && !empty($reachTruck->to_locationable_id)) {
                $reachTruck->toLocationable->updateIsEmpty(false);
            }

            $this->updateMasterPalletLastLocation($reachTruck);
        }

        if ($reachTruck->isDirty('is_transfered')) {
            $this->updateMasterPalletLastLocation($reachTruck);
            if ($reachTruck->to_locationable_type == Location::class && $reachTruck->to_locationable_id != Location::LOADING_LOCATION_ID) {
                $this->processUpdateOrderItemPalletDetails($reachTruck);
            }

            if($reachTruck->is_transfered) {
                if ($reachTruck->from_locationable_type == Location::class && $reachTruck->to_locationable_type == Warehouse::class) {
                    $reachTruck->toLocationable->updateIsEmpty(false);
                }

                if($reachTruck->from_locationable_type == Warehouse::class && $reachTruck->to_locationable_type == Location::class) {
                    $reachTruck->fromLocationable->updateIsEmpty(true);
                }
            }
        }

        /*
         * if pallet drop at warehouse location
         * then update warehouse location on pallet
         */
        if(($reachTruck->wasChanged('is_transfered') || $reachTruck->wasChanged('to_locationable_id')) && $reachTruck->to_locationable_type == Warehouse::class)
        {
            if($reachTruck->is_transfered) {
                $this->updateWarehouseLocationOnPallet($reachTruck);
            }
        }
    }

    public function updateWarehouseLocationOnPallet(ReachTruck  $reachTruck)
    {
        $reachTruck->pallet->updateLastWarehouseLocation($reachTruck->to_locationable_id);
    }

    public function updateMasterPalletLastLocation($reachTruck)
    {
        if ($reachTruck->is_transfered) {
            $locationableType = $reachTruck->to_locationable_type;
            $locationableId = $reachTruck->to_locationable_id;
        } else {
            $locationableType = $reachTruck->from_locationable_type;
            $locationableId = $reachTruck->from_locationable_id;
        }
        $reachTruck->pallet->masterPallet->updateLastLocationable($locationableType, $locationableId);
    }

    public function processUpdateOrderItemPalletDetails(ReachTruck $reachTruck)
    {
        foreach ($reachTruck->pallet->palletDetails as $key => $palletDetail) {
            if (!empty($palletDetail->orderItemPallet)) {
                $orderItemPalletDetail = OrderItemPalletDetails::where('order_item_id', $palletDetail->orderItemPallet->order_item_id)
                    ->where('pallet_name', $reachTruck->pallet->masterPallet->name)
                    ->first();

                if ($orderItemPalletDetail) {
                    $orderItemPalletDetail->updateTransferDetails($reachTruck->transferBy->name);
                }
            }
        }
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
