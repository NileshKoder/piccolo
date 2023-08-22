<?php

namespace App\Features\OrderManagement\Observers;

use App\Features\OrderManagement\Domains\Models\OrderItem;
use App\Features\OrderManagement\Domains\Models\OrderItemPallet;

class OrderItemPalletObserver
{
    public function deleting(OrderItemPallet $orderItemPallet)
    {
       if($orderItemPallet->orderItem->orderItemPalletDetails->whereNull('transfered_by')->count() == 0) {
           $orderItemPallet->orderItem->updateState(OrderItem::TRANSFERRED);
       } else {
           $orderItemPallet->orderItem->updateState(OrderItem::PARTIAL_TRANSFERRED);
       }
    }
}
