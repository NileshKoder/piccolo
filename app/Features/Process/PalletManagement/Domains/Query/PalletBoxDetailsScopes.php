<?php

namespace App\Features\Process\PalletManagement\Domains\Query;

use Illuminate\Database\Eloquent\Builder;

trait PalletBoxDetailsScopes
{
    public function scopeLimitBy(Builder $query, int $start, int $length): Builder
    {
        if ($length != -1) {
            return $query->offset($start)->limit($length);
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

    public function scopeOrderId(Builder $query, ?int $orderId)
    {
        if(!empty($orderId)) {
            return $query->whereHas('pallet.order', function($orderQuery) use($orderId) {
                return $orderQuery->id($orderId);
            });
        }

        return $query;
    }
}
