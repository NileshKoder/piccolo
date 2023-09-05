<?php

namespace App\Features\OrderManagement\Domains\Models;

use App\Features\Masters\Locations\Domains\Models\Location;
use App\Features\OrderManagement\Observers\OrderItemPalletObserver;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use App\Features\Masters\Warehouses\Domains\Models\Warehouse;
use App\Features\OrderManagement\Domains\Query\OrderItemPalletScopes;
use App\Features\Process\ReachTruck\Actions\ReachTruckAction;
use App\Features\Process\PalletManagement\Domains\Models\Pallet;
use App\Features\Process\PalletManagement\Domains\Models\PalletDetails;

class OrderItemPallet extends Model
{
    use OrderItemPalletScopes;

    protected $fillable = ['order_item_id', 'pallet_id', 'pallet_detail_id', 'weight'];

    public static function persistMapPallets(OrderItem $orderItem)
    {
        $palletDetails = $orderItem->getPalletsForMapping();

        if ($palletDetails->count() > 0) {
            $maxWeight = $orderItem->required_weight;
            $mappedWeight = ($orderItem->orderItemPallets->count() > 0) ? $orderItem->orderItemPallets->sum('weight') : 0;
            $remainingWeight = $maxWeight - $mappedWeight;
            $reachTruckAction = new ReachTruckAction();

            DB::beginTransaction();
            foreach ($palletDetails as $key => $palletDetail) {
                $orderItemPallet = OrderItemPallet::palletId($palletDetail->pallet_id)->palletDetailId($palletDetail->id)->first();

                if ($orderItemPallet) {
                    continue;
                }

                if ($remainingWeight >= $palletDetail->weight) {
                    $mappingWeight = $palletDetail->weight;
                } else if ($remainingWeight <= $palletDetail->weight) {
                    $mappingWeight = $remainingWeight;
                } else {
                    $mappingWeight = $palletDetail->weight;
                }

                $orderItemPalletData = [];

                $orderItemPalletData['pallet_id'] = $palletDetail->pallet_id;
                $orderItemPalletData['pallet_detail_id'] = $palletDetail->id;
                $orderItemPalletData['weight'] = $mappingWeight;

                $orderItemPallet = self::persistOrderItemPallet($orderItem, $orderItemPalletData);

                $reachTruckAction->createReachTruckFromPallet($palletDetail->pallet, Location::class, $orderItem->location_id);
                OrderItemPalletDetails::procressOrderItemPalletDetails($orderItemPallet, $mappingWeight);
                $mappedWeight += $mappingWeight;
                $remainingWeight -= $mappingWeight;

                if ($mappedWeight >= $maxWeight) {
                    $orderItem->updateState(OrderItem::MAPPED);
                    break;
                } else if ($mappedWeight < $maxWeight) {
                    $orderItem->updateState(OrderItem::PARTIAL_MAPPED);
                }
            }
            DB::commit();
        }
    }

    public function unMappedPallet()
    {
        $this->delete();
        $this->orderItem->reCalculateOrderItemState();
        return;
    }

    public static function persistOrderItemPallet(OrderItem $orderItem, array $orderItemPalletData)
    {
        return $orderItem->orderItemPallets()->create($orderItemPalletData);
    }

    public function pallet()
    {
        return $this->belongsTo(Pallet::class);
    }

    public function palletDetail()
    {
        return $this->belongsTo(PalletDetails::class, 'pallet_detail_id');
    }

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }

    public static function boot()
    {
        parent::boot();
        self::observe(OrderItemPalletObserver::class);
    }
}
