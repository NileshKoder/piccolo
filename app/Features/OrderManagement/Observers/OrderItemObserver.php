<?php

namespace App\Features\OrderManagement\Observers;

use App\Features\OrderManagement\Domains\Models\Order;
use App\Features\OrderManagement\Domains\Models\OrderItem;

class OrderItemObserver
{
    public function updated(OrderItem  $orderItem)
    {
        if($orderItem->isDirty('state')) {
            if($orderItem->state == OrderItem::CANCELLED) {
                if($orderItem->orderItemPallets->count() > 0) {
                    foreach ($orderItem->orderItemPallets as $orderItemPallet) {
                        $orderItemPallet->delete();
                    }
                }
            }
        }
    }
}
