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

    public function scopeToLocationableId(Builder $query, ?int $toLocationableId)
    {
        if (!empty($toLocationableId)) {
            return $query->where('to_locationable_id', $toLocationableId);
        }

        return $query;
    }

    public function scopeNotToLocationableType(Builder $query, ?string $toLocationAbleType)
    {
        if (!empty($toLocationAbleType)) {
            return $query->where('to_locationable_type', '!=', $toLocationAbleType);
        }

        return $query;
    }

    public function scopeToLocationableType(Builder $query, ?string $toLocationAbleType)
    {
        if (!empty($toLocationAbleType)) {
            return $query->where('to_locationable_type', $toLocationAbleType);
        }

        return $query;
    }

    public function scopeToLocationableIdIn(Builder $query, ?array $toLocationAbleIds)
    {
        if (!empty($toLocationAbleIds)) {
            return $query->whereIn('to_locationable_id', $toLocationAbleIds);
        }

        return $query;
    }

    public function scopeFromLocationableIdIn(Builder $query, ?array $fromLocationAbleIds)
    {
        if (!empty($fromLocationAbleIds)) {
            return $query->whereIn('from_locationable_id', $fromLocationAbleIds);
        }

        return $query;
    }
}
