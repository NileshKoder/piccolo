<?php

namespace App\Features\Masters\Boxes\Domains\Query;

use Illuminate\Database\Eloquent\Builder;

trait BoxScopes
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

    public function scopeIsEmpty(Builder $query, bool $isEmpty): Builder
    {
        return $query->where('is_empty', $isEmpty);
    }
}
