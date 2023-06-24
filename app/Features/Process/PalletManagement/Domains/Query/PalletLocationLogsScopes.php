<?php

namespace App\Features\Process\PalletManagement\Domains\Query;

use App\Features\Masters\Locations\Domains\Models\Location;
use Illuminate\Database\Eloquent\Builder;

trait PalletLocationLogsScopes
{
    public function scopeLocationableType(Builder $query, ?string $locationableType)
    {
        if (!empty($locationableType)) {
            return $query->with('locationable')->whereHasMorph('locationable', [Location::class], function ($locationableQry) use ($locationableType) {
                $locationableQry->type($locationableType);
            });
        }

        return $query;
    }
}
