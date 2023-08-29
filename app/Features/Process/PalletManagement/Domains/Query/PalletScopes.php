<?php

namespace App\Features\Process\PalletManagement\Domains\Query;

use App\Features\Masters\Locations\Domains\Models\Location;
use Illuminate\Database\Eloquent\Builder;

trait PalletScopes
{
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

    public function scopeMasterPalletName(Builder $query, string $masterPalletName)
    {
        if (!empty($masterPalletName)) {
            return $query->with('masterPallet')->whereHas('masterPallet', function ($qry) use ($masterPalletName) {
                $qry->where('name', $masterPalletName);
            });
        }
        return $query;
    }

    public function scopeMasterPalletId(Builder $query, ?int $masterPalletId)
    {
        if (!empty($masterPalletId)) {
            return $query->with('masterPallet')->whereHas('masterPallet', function ($qry) use ($masterPalletId) {
                $qry->id($masterPalletId);
            });
        }
        return $query;
    }

    public function scopecurrentLocationType(Builder $query, string $locationType)
    {
        if (!empty($locationType)) {
            return $query->with('currentPalletLocation')->whereHas('currentPalletLocation', function ($currentPalletLocationQry) use ($locationType) {
                $currentPalletLocationQry->locationableType($locationType);
            });
        }

        return $query;
    }

    public function scopeNonTransfered(Builder $query)
    {
        return $query->with('reachTruck')->whereHas('reachTruck', function ($reachTruckQry) {
            $reachTruckQry->NonTransfered();
        });
    }

    public function scopeSkuCodeId(Builder $query, ?int $skuCodeId): Builder
    {
        if($skuCodeId) {
            return $query->whereHas('palletDetails', function($q) use ($skuCodeId) {
                $q->where('sku_code_id', $skuCodeId);
            });
        }

        return $query;
    }

    public function scopeVariantId(Builder $query, ?int $variantId): Builder
    {
        if($variantId) {
            return $query->whereHas('palletDetails', function($q) use ($variantId) {
                $q->where('variant_id', $variantId);
            });
        }

        return $query;
    }

    public function scopeOrderId(Builder $query, ?int $orderId): Builder
    {
        if($orderId) {
            return $query->where('order_id', $orderId);
        }

        return $query;
    }

    public function scopeLocationId(Builder $query, ?int $locationId): Builder
    {
        if($locationId) {
            return $query->whereHas('masterPallet', function($q) use ($locationId) {
                $q->where('last_locationable_type', Location::class)
                    ->where('last_locationable_id', $locationId);
            });
        }

        return $query;
    }
}
