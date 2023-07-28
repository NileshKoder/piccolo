<?php

namespace App\Features\Process\ReachTruck\Domains\Models;

use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use App\Features\Process\PalletManagement\Domains\Models\Pallet;
use App\Features\Process\ReachTruck\Constants\ReachTruckConstants;
use App\Features\Process\ReachTruck\Observers\ReachTruckObserver;
use App\Features\Process\ReachTruck\Domains\Query\ReachTruckScopes;

class ReachTruck extends Model implements ReachTruckConstants
{
    use ReachTruckScopes;

    protected $fillable = [
        'pallet_id', 'from_locationable_type', 'from_locationable_id', 'to_locationable_type', 'to_locationable_id',
        'is_transfered', 'transfered_by', 'created_by'
    ];

    public static function persistReachTruck(array $reachTruckData, Pallet $pallet)
    {
        return $pallet->reachTruck()->updateOrCreate($reachTruckData);
    }

    public static function persitProcessPalletTransfer(array $requestData)
    {
        return DB::transaction(function () use ($requestData) {
            $reachTruck = ReachTruck::with('pallet.masterPallet', 'toLocationable')->find($requestData['reach_truck_id']);
            $reachTruck->transfered_by = $requestData['transfered_by'];
            $reachTruck->to_locationable_id = $requestData['to_locationable_id'];
            $reachTruck->is_transfered = true;
            $reachTruck->update();
        });
    }

    public static function persitUpdatePalletTransferDeatils(array $requestData, ReachTruck $reachTruck)
    {
        return DB::transaction(function () use ($requestData, $reachTruck) {
            $reachTruck->transfered_by = $requestData['transfered_by'];
            $reachTruck->to_locationable_type = $requestData['to_locationable_type'];
            $reachTruck->to_locationable_id = (int) $requestData['to_locationable_id'];
            $reachTruck->update();
        });
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

    public static function boot()
    {
        parent::boot();
        self::observe(ReachTruckObserver::class);
    }
}
