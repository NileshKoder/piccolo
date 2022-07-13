<?php

namespace App\Features\Masters\Users\Domains\Queries;

use Illuminate\Database\Eloquent\Builder;

trait UserScopes
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

    public function scopeRole(Builder $query, ?string $role)
    {
        if (!empty($role)) {
            return $query->where('role', $role);
        }

        return $query;
    }
}
