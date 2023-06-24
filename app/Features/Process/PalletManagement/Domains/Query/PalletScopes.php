<?php

namespace App\Features\Process\PalletManagement\Domains\Query;

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

    public function scopeMasterPalletId(Builder $query, int $masterPalletId)
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
}
