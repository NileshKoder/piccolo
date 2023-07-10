<?php

namespace App\Features\OrderManagement\Domains\Query;

use Illuminate\Database\Eloquent\Builder;

trait OrderScopes
{
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

    public function scopeNotInState(Builder $query, ?array $states)
    {
        if (!empty($states) && count($states) > 0) {
            return $query->whereNotIn('state', $states);
        }

        return $query;
    }

    public function scopeState(Builder $query, ?string $state)
    {
        if (!empty($state)) {
            return $query->where('state', $state);
        }

        return $query;
    }
}
