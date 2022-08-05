<?php

namespace App\Features\Process\PalletManagement\Domains\Query;

use Illuminate\Database\Eloquent\Builder;

trait PalletDetailsScopes
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

    public function scopeMasterPalletId(Builder $query, ?int $masterPalletId)
    {
        if (!empty($masterPalletId)) {
            return $query->with('pallet.masterPallet')
                ->whereHas('pallet', function ($palletQuery) use ($masterPalletId) {
                    $palletQuery->masterPalletId($masterPalletId);
                });
        }
        return $query;
    }

    public function scopeSkuCodeId(Builder $query, ?int $skuCodeId)
    {
        if (!empty($skuCodeId)) {
            return $query->with('skuCode')->where('sku_code_id', $skuCodeId);
        }
        return $query;
    }

    public function scopeVariantId(Builder $query, ?int $varinatId)
    {
        if (!empty($varinatId)) {
            return $query->with('variant')->where('variant_id', $varinatId);
        }
        return $query;
    }
}
