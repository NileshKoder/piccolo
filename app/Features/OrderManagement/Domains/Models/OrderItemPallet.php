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
            foreach ($pallets as $key => $pallet) {
                $weightInPallet = $pallet->palletDetails->where('sku_code_id', $orderItem->sku_code_id)->where('variant_id', $orderItem->variant_id)->sum('weight');
                if ($maxWeight > $weightInPallet) {
                    $orderItem->orderItemPallets()->create(['pallet_id' => $pallet->id]);
                    $maxWeight = $maxWeight - $weightInPallet;
                    $orderItem->updateState(OrderItem::PARTIAL_MAPPED);
                }

                if ($maxWeight <= $weightInPallet) {
                    $orderItem->updateState(OrderItem::MAPPED);
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
