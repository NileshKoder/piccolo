<?php

namespace App\Features\OrderManagement\Domains\Query;

use Illuminate\Database\Eloquent\Builder;

trait OrderItemPalletScopes
{
    public function scopePalletId(Builder $query, ?int $palletId)
    {
        if (!empty($palletId)) {
            return $query->where('pallet_id', $palletId);
        }

        return $query;
    }

    public function scopePalletDetailId(Builder $query, ?int $palletDetailId)
    {
        if (!empty($palletDetailId)) {
            return $query->where('pallet_detail_id', $palletDetailId);
        }

        return $query;
    }
}
