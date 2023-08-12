<?php

namespace App\Features\OrderManagement\Observers;

use App\Features\OrderManagement\Domains\Models\OrderItem;
use App\Features\OrderManagement\Domains\Models\OrderItemPallet;

class OrderItemPalletObserver
{
    public function deleting(OrderItemPallet $orderItemPallet)
    {
        $orderItemPallet->orderItem->updateState(OrderItem::TRANSFERED);
    }
}
