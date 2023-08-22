<?php

namespace App\Features\OrderManagement\Observers;

use App\Features\OrderManagement\Domains\Models\Order;
use App\Features\OrderManagement\Domains\Models\OrderItem;
use App\Features\OrderManagement\Domains\Models\OrderItemPallet;

class OrderItemPalletObserver
{
    public function deleted(OrderItemPallet $orderItemPallet)
    {
        if($orderItemPallet->orderItem->orderItemPallets->count() == 0) {
           $orderItemPallet->orderItem->updateState(OrderItem::TRANSFERRED);
           $orderItemPallet->orderItem->order->updateState(Order::TRANSFERRED);
        } else {
           $orderItemPallet->orderItem->updateState(OrderItem::PARTIAL_TRANSFERRED);
        }
    }
}
