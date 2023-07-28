<?php

namespace App\Features\OrderManagement\Domains\Models;

use App\Features\Masters\Warehouses\Domains\Models\Warehouse;
use Illuminate\Database\Eloquent\Model;
use App\Features\Process\PalletManagement\Domains\Models\Pallet;

class OrderItemPallet extends Model
{
    protected $fillable = ['pallet_id', 'is_transfered'];

    public static function persistOrderItemPallets(OrderItem $orderItem)
    {
        $pallets = Pallet::doesntHave('orderItemPallets')
            ->whereHas('currentPalletLocation', function ($q) {
                $q->where('locationable_type', Warehouse::class);
            })
            ->whereHas('palletDetails', function ($q) use ($orderItem) {
                $q->skuCodeId($orderItem->sku_code_id)->variantId($orderItem->variant_id);
            })->get();

        if ($pallets->count() > 0) {
            $maxWeight = $orderItem->required_weight;
            $mappedQty = 0;
            foreach ($pallets as $key => $pallet) {
                $orderItem->orderItemPallets()->create(['pallet_id' => $pallet->id]);

                $weightInPallet = $pallet->palletDetails->where('sku_code_id', $orderItem->sku_code_id)->where('variant_id', $orderItem->variant_id)->sum('weight');
                $mappedQty += $weightInPallet;

                if ($mappedQty >= $maxWeight) {
                    $orderItem->updateState(OrderItem::MAPPED);
                    break;
                } else if ($mappedQty < $maxWeight) {
                    $orderItem->updateState(OrderItem::PARTIAL_MAPPED);
                }
            }
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

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }
}
