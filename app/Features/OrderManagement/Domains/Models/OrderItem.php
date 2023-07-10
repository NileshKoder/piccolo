<?php

namespace App\Features\OrderManagement\Domains\Models;

use Illuminate\Database\Eloquent\Model;
use App\Features\OrderManagement\Domains\Models\Order;
use App\Features\Masters\SkuCodes\Domains\Models\SkuCode;
use App\Features\Masters\Variants\Domains\Models\Variant;
use App\Features\Masters\Locations\Domains\Models\Location;
use App\Features\OrderManagement\Helpers\OrderMappingHelper;
use App\Features\OrderManagement\Constants\OrderItemConstants;

class OrderItem extends Model implements OrderItemConstants
{
    protected $fillable = ['order_id', 'sku_code_id', 'variant_id', 'location_id', 'required_weight', 'pick_up_date', 'state'];

    public $appends = ['mapped_required_weight'];

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

    public static function persistCreateOrderItemDetail(Order $order, array $data): ?OrderItem
    {
        $data['pick_up_date'] = date('Y-m-d', strtotime($data['pick_up_date']));
        $orderItem = $order->ordeItemDetails()->create($data);

        $orderMappingHelper = new OrderMappingHelper();
        $orderMappingHelper->mapPalletsVaiOrder($orderItem);
        return $orderItem;
    }

    public static function persistDeleteOrderItemDetail(Order $order, array $ids)
    {
        return $order->ordeItemDetails()->whereNotIn('id', $ids)->delete();
    }

    public static function persistUpdateOrderItemDetail(Order $order, array $data): ?OrderItem
    {
        $data['pick_up_date'] = date('Y-m-d', strtotime($data['pick_up_date']));
        $orderItem = OrderItem::find($data['order_item_detail_id']);
        $orderItem->update($data);

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
        self::observe(OrderItemObserver::class);
    }
}
