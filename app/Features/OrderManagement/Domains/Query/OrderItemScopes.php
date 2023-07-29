<?php

namespace App\Features\OrderManagement\Domains\Query;

use Carbon\Carbon;
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

    public function scopePickUpDateLessThanToday(Builder $query)
    {
        return $query->whereDate('pick_up_date', '<=', Carbon::now()->today());
    }
}
