<?php

namespace App\Features\Masters\Warehouses\Domains\Query;

use App\Features\Masters\CrateCodes\Domains\Models\CrateCode;
use Illuminate\Database\Eloquent\Builder;

trait WarehouseScopes
{
    /**
     * Query Scopes
     */
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

    public function scopeIsEmpty(Builder $query): Builder
    {
        return $query->where('is_empty', 1);
    }

    public function scopeIsNotEmpty(Builder $query): Builder
    {
        return $query->where('is_empty', 0);
    }

    public function scopeIdIn(Builder $query, ?array $ids)
    {
        if (!empty($ids) && count($ids) > 0) {
            return $query->whereIn('id', $ids);
        }
        return $query;
    }
}
