<?php

namespace App\Features\OrderManagement\Domains\Models;

use App\Features\Masters\Warehouses\Domains\Models\Warehouse;
use App\Features\OrderManagement\Observers\OrderItemObserver;
use App\Features\Process\PalletManagement\Domains\Models\PalletDetails;
use Illuminate\Database\Eloquent\Model;
use App\Features\OrderManagement\Domains\Models\Order;
use App\Features\Masters\SkuCodes\Domains\Models\SkuCode;
use App\Features\Masters\Variants\Domains\Models\Variant;
use App\Features\Masters\Locations\Domains\Models\Location;
use App\Features\OrderManagement\Constants\OrderItemConstants;
use App\Features\OrderManagement\Domains\Query\OrderItemScopes;
use App\Features\OrderManagement\Domains\Models\OrderItemPallet;

class OrderItem extends Model implements OrderItemConstants
{
    use OrderItemScopes;

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

    public function orderItemPalletDetails()
    {
        return $this->hasMany(OrderItemPalletDetails::class, 'order_item_id');
    }

    public static function persistCreateOrderItem(Order $order, array $data): ?OrderItem
    {
        $data['pick_up_date'] = date('Y-m-d', strtotime($data['pick_up_date']));
        $orderItem = $order->orderItems()->create($data);

        return $orderItem;
    }

    public static function persistDeleteOrderItemDetail(Order $order, array $ids)
    {
        return $order->orderItems()->whereNotIn('id', $ids)->delete();
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

    public function mapPallets()
    {
        return OrderItemPallet::persistMapPallets($this);
    }

    public function updateState(string $state)
    {
        $this->state = $state;
        $this->update();
    }

    public function reCalculateOrderItemState()
    {
        $this->refresh();
        if ($this->orderItemPallets->count() > 0) {
            $mappedWeight = $this->orderItemPallets->sum('weight');

            if ($mappedWeight == 0) {
                $this->updateState(OrderItem::CREATED);
            } elseif ($mappedWeight >= $this->required_weight) {
                $this->updateState(OrderItem::MAPPED);
            } elseif ($mappedWeight <= $this->required_weight) {
                $this->updateState(OrderItem::PARTIAL_MAPPED);
            }
        } else {
            $this->updateState(OrderItem::CREATED);
        }
    }

    public function isItemHasAllDetails(): bool
    {
        return !empty($this->sku_code_id) &&
               !empty($this->variant_id) &&
               !empty($this->location_id) &&
               !empty($this->required_weight) &&
               !empty($this->pick_up_date);
    }

    public function getPalletsForMapping()
    {
        return PalletDetails::with('pallet.masterPallet.lastLocation')
            ->doesntHave('orderItemPallet')
            ->whereHas('pallet', function ($palletQry) {
                $palletQry->doesntHave('orderItemPallet')
                    ->whereHas('masterPallet', function ($masterPalletQry) {
                        $masterPalletQry->where('last_locationable_type', Warehouse::class);
                    });
            })
            ->skuCodeId($this->sku_code_id)
//            ->variantId($this->variant_id)
            ->orderBy('batch_date', 'ASC')
            ->get();
    }

    public static function boot()
    {
        parent::boot();
        self::observe(OrderItemObserver::class);
    }
}
