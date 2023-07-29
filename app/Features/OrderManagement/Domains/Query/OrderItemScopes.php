<?php

namespace App\Features\OrderManagement\Domains\Query;

use Illuminate\Database\Eloquent\Builder;

trait OrderItemScopes
{
    public function scopeStateIn(Builder $query, ?array $states)
    {
        if (!empty($states)) {
            return $query->whereIn('state', $states);
        }

        return $query;
    }
}
