<?php

namespace App\Features\Process\ReachTruck\Domains\Query;

use Illuminate\Database\Eloquent\Builder;

trait ReachTruckScopes
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

    public function scopeNonTransfered(Builder $query)
    {
        return $query->where('is_transfered', false);
    }

    public function scopeFromLocationableType(Builder $query, ?string $fromLocationableType)
    {
        if (!empty($fromLocationableType)) {
            return $query->where('from_locationable_type', $fromLocationableType);
        }

        return $query;
    }

    public function scopeFromLocationableId(Builder $query, ?int $fromLocationableId)
    {
        if (!empty($fromLocationableId)) {
            return $query->where('from_locationable_id', $fromLocationableId);
        }

        return $query;
    }
}
