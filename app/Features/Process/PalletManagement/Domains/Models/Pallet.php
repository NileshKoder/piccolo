<?php

namespace App\Features\Process\PalletManagement\Domains\Models;

use App\Helpers\Traits\BelongsTo;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use App\Features\Masters\MasterPallet\Domains\Models\MasterPallet;
use App\Features\Process\PalletManagement\Constants\PalletContants;
use App\Features\Process\PalletManagement\Observers\PalletObserver;
use App\Features\Process\PalletManagement\Domains\Query\PalletScopes;
use App\Features\Process\PalletManagement\Domains\Models\PalletDetails;
use App\Features\Process\PalletManagement\Domains\Models\PalletBoxDetails;
use App\Features\Process\PalletManagement\Domains\Models\PalletLocationLogs;
use App\Features\Process\ReachTruck\Actions\ReachTruckAction;
use App\Features\Process\ReachTruck\Domains\Models\ReachTruck;

class Pallet extends Model implements PalletContants
{
    use PalletScopes, BelongsTo;

    protected $fillable = ['master_pallet_id', 'created_by', 'updated_by'];

    public static function persistCreatePallet(array $palletData): ?Pallet
    {
        $pallet = null;
        $pallet = DB::transaction(function () use ($palletData) {
            $pallet = Pallet::create($palletData['pallet']);
            if (array_key_exists('pallet_details', $palletData)) {
                self::updateOrCreatePalletDetails($pallet, $palletData['pallet_details']);
            }

            // if (array_key_exists('pallet_box_details', $palletData)) {
            //     self::createPalletDetails($pallet, $palletData['pallet_box_details']);
            // }

            self::createPalletLocation($pallet, $palletData['pallet_location']);

            if ($palletData['is_request_for_warehouse']) {
                $reachTruckAction = new ReachTruckAction();
                $reachTruckAction->createReachTruckFromPallet($pallet);
            }

            return $pallet;
        });
        return $pallet;
    }

    public static function persistUpdatePallet(Pallet $pallet, array $palletData)
    {
        DB::transaction(function () use ($pallet, $palletData) {
            $pallet->update($palletData['pallet']);
            if (array_key_exists('pallet_details', $palletData)) {
                self::updateOrCreatePalletDetails($pallet, $palletData['pallet_details']);
            }

            // if (array_key_exists('pallet_box_details', $palletData)) {
            //     self::createPalletDetails($pallet, $palletData['pallet_box_details']);
            // }
            self::createPalletLocation($pallet, $palletData['pallet_location']);

            if ($pallet->palletDetails->count() == 0 && $pallet->palletBoxDetails->count() == 0) {
                self::persistDeletePallet($pallet);
            }

            if ($palletData['is_request_for_warehouse']) {
                $reachTruckAction = new ReachTruckAction();
                $reachTruckAction->createReachTruckFromPallet($pallet);
            }

            return $pallet;
        });
        return $pallet;
    }

    public static function updateOrCreatePalletDetails(Pallet $pallet, ?array $palletDetails)
    {
        $updatedIds = array();
        if (!empty($palletDetails) && count($palletDetails) > 0) {
            foreach ($palletDetails as $key => $palletDetail) {
                $palletDetails = PalletDetails::persistUpdateOrCreatePalletDetails($pallet, $palletDetail);

                if (!empty($palletDetails)) {
                    array_push($updatedIds, $palletDetails->id);
                }
            }
        } else {
            $updatedIds = [0];
        }

        if (!empty($updatedIds)) {
            PalletDetails::persistdeletePalletDetailsWhereNotInIds($pallet, $updatedIds);
        }
    }

    public static function createPalletBoxDetails(Pallet $pallet, array $palletBoxDetails)
    {
        foreach ($palletBoxDetails as $key => $palletBoxDetail) {
            PalletBoxDetails::persistPalletBoxDetails($pallet, $palletBoxDetail);
        }
    }

    public static function createPalletLocation(Pallet $pallet, $palletLocationDetails)
    {
        return PalletLocationLogs::persistPalletLocation($pallet, $palletLocationDetails);
    }

    public static function persistDeletePallet(Pallet $pallet)
    {
        $pallet->palletLocationLogs()->delete();
        return $pallet->delete();
    }

    public function masterPallet()
    {
        return $this->belongsTo(MasterPallet::class);
    }

    public function palletDetails()
    {
        return $this->hasMany(PalletDetails::class);
    }

    public function palletBoxDetails()
    {
        return $this->hasMany(PalletBoxDetails::class);
    }

    public function palletLocationLogs()
    {
        return $this->hasMany(PalletLocationLogs::class);
    }

    public function currentPalletLocation()
    {
        return $this->hasOne(PalletLocationLogs::class)->orderBy('id', 'DESC')->latest('created_at');
    }

    public function reachTruck()
    {
        return $this->hasOne(ReachTruck::class);
    }

    public static function boot()
    {
        parent::boot();
        self::observe(PalletObserver::class);
    }

    public static function getCreateValidationRules()
    {
        return [
            "location_id" => 'required|exists:locations,id',
            "master_pallet_id" => 'required|exists:master_pallets,id',
            "pallet_details.*.sku_code_id" => 'required|exists:sku_codes,id',
            "pallet_details.*.variant_id" => 'required|exists:variants,id',
            "pallet_details.*.weight" => 'required',
            "pallet_details.*.batch" => 'required',
        ];
    }
}
