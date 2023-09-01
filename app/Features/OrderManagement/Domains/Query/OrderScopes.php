<?php

namespace App\Features\OrderManagement\Domains\Query;

use Illuminate\Database\Eloquent\Builder;

trait OrderScopes
{
    public function scopeSearch(Builder $query, ?string $args): Builder
    {
        if ($args) {
            return $query->where('name', 'like', "%{$args}%");
        }
        return $query;
    }

    public function scopeLimitBy(Builder $query, int $start, int $length): Builder
    {
        if ($length != -1) {
            return $query->offset($start)->limit($length);
        }
        return $query;
    }
    public function scopeOrder(Builder $query, array $order): Builder
    {
        if ($order) {
            $columns = ['actions', 'id', 'name'];
            return $query->orderBy($columns[$order[0]['column']], $order[0]['dir']);
        }
        return $query;
    }

    public function scopeNotInState(Builder $query, ?array $states): Builder
    {
        if (!empty($states) && count($states) > 0) {
            return $query->whereNotIn('state', $states);
        }

        return $query;
    }

    public function scopeState(Builder $query, ?string $state): Builder
    {
        if (!empty($state)) {
            return $query->where('state', $state);
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

    public function scopeStateNotIn(Builder $query, ?array $states): Builder
    {
        if (!empty($states)) {
            return $query->whereNotIn('state', $states);
        }

        return $query;
    }

    public function scopeSkuCodeId(Builder $query, ?int $skuCodeId): Builder
    {
        if(!empty($skuCodeId)) {
            $query->whereHas('orderItems', function($q) use ($skuCodeId) {
                $q->skuCodeId($skuCodeId);
            });
        }

        return $query;
    }

    public function scopeVariantId(Builder $query, ?int $variantId): Builder
    {
        if(!empty($variantId)) {
            $query->whereHas('orderItems', function($q) use ($variantId) {
                $q->variantId($variantId);
            });
        }

        return $query;
    }

    public function scopeLocationId(Builder $query, ?int $locationId): Builder
    {
        if(!empty($locationId)) {
            $query->whereHas('orderItems', function($q) use ($locationId) {
                $q->locationId($locationId);
            });
        }

        return $query;
    }

    public function scopePickUpDate(Builder $query, ?string $pickUpDate): Builder
    {
        if(!empty($pickUpDate)) {
            $query->whereHas('orderItems', function($q) use ($pickUpDate) {
                $q->pickUpDate($pickUpDate);
            });
        }

        return $query;
    }
}
