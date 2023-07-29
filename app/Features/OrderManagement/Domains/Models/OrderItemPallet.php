<?php

namespace App\Features\OrderManagement\Domains\Models;

use App\Features\Masters\Locations\Domains\Models\Location;
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
        $palletDetails = PalletDetails::with('pallet.masterPallet')
            ->doesntHave('orderItemPallet')
            ->whereHas('pallet', function ($palletQry) {
                $palletQry->doesntHave('orderItemPallet')
                    ->whereHas('masterPallet', function ($masterPalletQry) {
                        $masterPalletQry->where('last_locationable_type', Warehouse::class);
                    });
            })
            ->skuCodeId($orderItem->sku_code_id)
            ->variantId($orderItem->variant_id)
            ->orderBy('batch_date', 'ASC')
            ->get();

        if ($palletDetails->count() > 0) {
            $maxWeight = $orderItem->required_weight;
            $mappedQty = $orderItem?->orderItemPallets?->sum('weight') ?? 0;

            $reachTruckAction = new ReachTruckAction();

            DB::beginTransaction();
            foreach ($palletDetails as $key => $palletDetail) {
                $orderItemPallet = OrderItemPallet::palletId($palletDetail->pallet_id)->palletDetailId($palletDetail->od)->first();

                if ($orderItemPallet) {
                    continue;
                }

                $orderItem->orderItemPallets()->create([
                    'pallet_id' => $palletDetail->pallet_id,
                    'pallet_detail_id' => $palletDetail->id,
                    'weight' => $palletDetail->weight
                ]);

                $reachTruckAction->createReachTruckFromPallet($palletDetail->pallet, Location::class, $orderItem->location_id);

                $mappedQty += $palletDetail->weight;

                if ($mappedQty >= $maxWeight) {
                    $orderItem->updateState(OrderItem::MAPPED);
                    break;
                } else if ($mappedQty < $maxWeight) {
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
}
