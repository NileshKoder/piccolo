<?php

namespace App\Features\OrderManagement\Domains\Models;

use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use App\Features\OrderManagement\Constants\OrderConstants;
use App\Features\OrderManagement\Domains\Models\OrderItem;
use App\Features\OrderManagement\Domains\Query\OrderScopes;

class Order extends Model implements OrderConstants
{
    use OrderScopes;

    protected $fillable = ['order_number', 'state', 'created_by', 'updated_by'];

    public function ordeItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updator()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public static function persristCreateOrder(array $data): ?Order
    {
        $order = null;
        $order = DB::transaction(function () use ($data) {
            $order = Order::create($data);
            self::createOrderItems($order, $data);

            return $order;
        });

        return $order;
    }

    public static function createOrderItems(Order $order, array $data)
    {
        foreach ($data['order_item_details'] as $key => $orderItem) {
            OrderItem::persistCreateOrderItem($order, $orderItem);
        }

        return $order->ordeItems;
    }

    public static function persristUpdateOrder(Order $order, array $data)
    {
        DB::transaction(function () use ($data, $order) {
            $order->update($data);
            self::updateOrderItems($order, $data);

            return $order;
        });
    }

    public static function updateOrderItems(Order $order, array $data)
    {
        $updateIds = [];

        foreach ($data['order_item_details'] as $key => $orderItem) {
            if ($orderItem['order_item_id']) {
                array_push($updateIds, $orderItem['order_item_id']);
                OrderItem::persistUpdateOrderItem($order, $orderItem);
            } else {
                $orderItem = OrderItem::persistCreateOrderItem($order, $orderItem);
                array_push($updateIds, $orderItem->id);
            }
        }

        if (count($updateIds) > 0) {
            OrderItem::persistDeleteOrderItemDetail($order, $updateIds);
        }

        return $order->ordeItemDetails;
    }

    public function updateState(string $state)
    {
        $this->state = $state;
        $this->update();

        return $this;
    }

    public function isOrderHasAllDetails()
    {
        return $this->ordeItems()
            ->whereNotNull('sku_code_id')
            ->whereNotNull('variant_id')
            ->whereNotNull('location_id')
            ->whereNotNull('required_weight')
            ->whereNotNull('pick_up_date')
            ->count() > 0 ? false : true;
    }
}
