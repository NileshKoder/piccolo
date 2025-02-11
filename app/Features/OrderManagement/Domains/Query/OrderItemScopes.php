<?php

namespace App\Features\OrderManagement\Domains\Query;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use function strtotime;

trait OrderItemScopes
{
    public function scopeLimitBy(Builder $query, int $start, int $length): Builder
    {
        if ($length != -1) {
            return $query->offset($start)->limit($length);
        }
        return $query;
    }

    public function scopeStateIn(Builder $query, ?array $states): Builder
    {
        if (!empty($states)) {
            return $query->whereIn('state', $states);
        }

        return $query;
    }

    public function scopePickUpDateLessThanToday(Builder $query): Builder
    {
        return $query->whereDate('pick_up_date', '<=', Carbon::now()->today());
    }

    public function scopeSkuCodeId(Builder $query, ?int $skuCodeId): Builder
    {
        if(!empty($skuCodeId)) {
            return $query->where('sku_code_id', $skuCodeId);
        }

        return $query;
    }

    public function scopeVariantId(Builder $query, ?int $variantId): Builder
    {
        if(!empty($variantId)) {
            return $query->where('variant_id', $variantId);
        }

        return $query;
    }

    public function scopeLocationId(Builder $query, ?int $locationId): Builder
    {
        if(!empty($locationId)) {
            return $query->where('location_id', $locationId);
        }

        return $query;
    }

    public function scopePickUpDate(Builder $query, ?string $pickUpDate): Builder
    {
        if(!empty($pickUpDate)) {
            $pickUpDate = date('Y-m-d', strtotime($pickUpDate));
            return $query->whereDate('pick_up_date', $pickUpDate);
        }

        return $query;
    }

    public function scopeOrderId(Builder $query, ?int $orderId): Builder
    {
        if(!empty($orderId)) {
            return $query->whereHas('order', function($q) use($orderId) {
                return $q->id($orderId);
            });
        }

        return $query;
    }

    public function scopeOrderState(Builder $query, ?string $state): Builder
    {
        if(!empty($state)) {
            return $query->whereHas('order', function($q) use($state) {
                return $q->state($state);
            });
        }

        return $query;
    }
}
