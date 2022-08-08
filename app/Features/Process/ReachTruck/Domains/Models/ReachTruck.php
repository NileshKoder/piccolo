<?php

namespace App\Features\Process\ReachTruck\Domains\Models;

use App\Features\Process\PalletManagement\Domains\Models\Pallet;
use App\User;
use Illuminate\Database\Eloquent\Model;

class ReachTruck extends Model
{
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

    public function transferedBy()
    {
        return $this->belongsTo(User::class, 'transfered_by');
    }
}
