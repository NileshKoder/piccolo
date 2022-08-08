<?php

namespace App\Features\Process\ReachTruck\Domains\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use App\Features\Process\PalletManagement\Domains\Models\Pallet;
use App\Features\Process\ReachTruck\Domains\Query\ReachTruckScopes;

class ReachTruck extends Model
{
    use ReachTruckScopes;

    protected $fillable = [
        'pallet_id', 'from_locationable_type', 'from_locationable_id', 'to_locationable_type', 'to_locationable_id',
        'is_transfered', 'transfered_by', 'created_by'
    ];

    public static function persistReachTruck(array $reachTruckData, Pallet $pallet)
    {
        return $pallet->reachTruck()->create($reachTruckData);
    }

    public function pallet()
    {
        return $this->belongsTo(Pallet::class);
    }

    public function fromLocationable()
    {
        return $this->morphTo();
    }

    public function toLocationable()
    {
        return $this->morphTo();
    }

    public function transferBy()
    {
        return $this->belongsTo(User::class, 'transfered_by');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
