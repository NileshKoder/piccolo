<?php

namespace App\Features\OrderManagement\Observers;

use App\Features\OrderManagement\Domains\Models\Order;
use App\Features\OrderManagement\Domains\Models\OrderItem;

class OrderItemObserver
{
    public function updated(OrderItem  $orderItem)
    {
        //
    }
}
