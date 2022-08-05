<?php

namespace App\Features\Masters\MasterPallet\Domains\Query;

use Illuminate\Database\Eloquent\Builder;

trait MasterPalletScopes
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

    public function scopeId(Builder $query, ?int $id): Builder
    {
        if (!empty($id)) {
            return $query->where('id', $id);
        }

        return $query;
    }
}
