<?php

namespace App\Features\OrderManagement\Helpers;

use App\Features\Masters\Warehouses\Domains\Models\Warehouse;
use App\Features\OrderManagement\Domains\Models\OrderItem;
use App\Features\Process\PalletManagement\Domains\Models\Pallet;

class OrderMappingHelper
{
    public function mapPalletsVaiOrder(OrderItem $orderItem)
    {
        $palletsInWarehouse = $this->getPalletFromWarehouse($orderItem);

        dd($palletsInWarehouse, "DD in OrderMappingHelper");
    }

    public function getPalletFromWarehouse(OrderItem $orderItem)
    {
        $pallets = Pallet::whereHas('currentPalletLocation', function ($q) {
            $q->where('locationable_type', Warehouse::class);
        })
            ->whereHas('palletDetails', function ($q) use ($orderItem) {
                $q->where('sku_id', $orderItem->sku_id)->where('variant_id', $orderItem->variant_id);
            })
            ->get();
    }
}
