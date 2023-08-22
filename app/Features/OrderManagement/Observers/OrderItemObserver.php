<?php

namespace App\Features\OrderManagement\Observers;

use App\Features\OrderManagement\Domains\Models\Order;
use App\Features\OrderManagement\Domains\Models\OrderItem;

class OrderItemObserver
{
    public function updated(OrderItem  $orderItem)
    {
        if($orderItem->isDirty('state')) {
            if ($orderItem->state == OrderItem::TRANSFERED) {
                $this->processUpdateOrderState($orderItem->order);
            }
        }
    }

    public function processUpdateOrderState(Order $order)
    {
        if($order->orderItems()->count() == $order->orderItems()->where('state', OrderItem::TRANSFERED)->count())
        {
            $order->updateState(Order::COMPLETED);
        }
    }
}
