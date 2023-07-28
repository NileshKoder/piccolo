<?php

namespace App\Features\OrderManagement\Domains\Models;

use Illuminate\Database\Eloquent\Model;
use App\Features\OrderManagement\Domains\Models\Order;
use App\Features\Masters\SkuCodes\Domains\Models\SkuCode;
use App\Features\Masters\Variants\Domains\Models\Variant;
use App\Features\Masters\Locations\Domains\Models\Location;
use App\Features\OrderManagement\Constants\OrderItemConstants;
use App\Features\OrderManagement\Domains\Models\OrderItemPallet;

class OrderItem extends Model implements OrderItemConstants
{
    protected $fillable = ['order_id', 'sku_code_id', 'variant_id', 'location_id', 'required_weight', 'pick_up_date', 'state'];

    public $appends = [];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function skuCode()
    {
        return $this->belongsTo(SkuCode::class);
    }

    public function variant()
    {
        return $this->belongsTo(Variant::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function orderItemPallets()
    {
        return $this->hasMany(OrderItemPallet::class, 'order_item_id');
    }

    public static function persistCreateOrderItem(Order $order, array $data): ?OrderItem
    {
        $data['pick_up_date'] = date('Y-m-d', strtotime($data['pick_up_date']));
        $orderItem = $order->ordeItems()->create($data);

        return $orderItem;
    }

    public static function persistDeleteOrderItemDetail(Order $order, array $ids)
    {
        return $order->ordeItems()->whereNotIn('id', $ids)->delete();
    }

    public static function persistUpdateOrderItem(Order $order, array $data): ?OrderItem
    {
        $data['pick_up_date'] = date('Y-m-d', strtotime($data['pick_up_date']));
        // $data['state'] = self::CREATED;
        $orderItem = OrderItem::find($data['order_item_id']);
        $orderItem->update($data);
        $orderItem->orderItemPallets()->delete();

        return $orderItem;
    }

    public function updateState(string $state)
    {
        $this->state = $state;
        $this->update();
    }

    public static function boot()
    {
        parent::boot();
        // self::observe(OrderItemObserver::class);
    }
}
