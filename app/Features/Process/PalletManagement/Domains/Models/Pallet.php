<?php

namespace App\Features\Process\PalletManagement\Domains\Models;

use App\Features\OrderManagement\Domains\Models\Order;
use App\Helpers\Traits\BelongsTo;
use Exception;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use App\Features\Masters\Locations\Domains\Models\Location;
use App\Features\Masters\Warehouses\Domains\Models\Warehouse;
use App\Features\Process\ReachTruck\Actions\ReachTruckAction;
use App\Features\Process\ReachTruck\Domains\Models\ReachTruck;
use App\Features\OrderManagement\Domains\Models\OrderItemPallet;
use App\Features\Masters\MasterPallet\Domains\Models\MasterPallet;
use App\Features\Process\PalletManagement\Constants\PalletContants;
use App\Features\Process\PalletManagement\Observers\PalletObserver;
use App\Features\Process\PalletManagement\Domains\Query\PalletScopes;
use App\Features\Process\PalletManagement\Domains\Models\PalletDetails;
use App\Features\Process\PalletManagement\Domains\Models\PalletBoxDetails;
use App\Features\Process\PalletManagement\Domains\Models\PalletLocationLogs;
use SebastianBergmann\Diff\Line;

class Pallet extends Model implements PalletContants
{
    use PalletScopes, BelongsTo;

    protected $fillable = ['master_pallet_id', 'loading_transfer_date','order_id', 'warehouse_id', 'created_by', 'updated_by'];

    public static function persistCreatePallet(array $palletData): ?Pallet
    {
        return DB::transaction(function () use ($palletData) {
            $pallet = Pallet::create($palletData['pallet']);

            self::updateMasterPalletLastLocation($pallet, $palletData['pallet']['location_id']);

            if (array_key_exists('pallet_details', $palletData)) {
                self::updateOrCreatePalletDetails($pallet, $palletData['pallet_details']);
            }

             if (array_key_exists('pallet_box_details', $palletData)) {
                 self::updateOrCreatePalletBoxDetails($pallet, $palletData['pallet_box_details']);
             }

            if ($palletData['is_request_for_warehouse']) {
                $reachTruckAction = new ReachTruckAction();
                $reachTruckAction->createReachTruckFromPallet($pallet, Warehouse::class);
            }

            if (isset($palletData['is_request_for_loading']) && $palletData['is_request_for_loading']) {
                $reachTruckAction = new ReachTruckAction();
                $reachTruckAction->createReachTruckFromPallet($pallet, Location::class, Location::LOADING_LOCATION_ID);
            }

            return $pallet;
        });
    }

    public static function persistUpdatePallet(Pallet $pallet, array $palletData)
    {
        DB::transaction(function () use ($pallet, $palletData) {
            $pallet->update($palletData['pallet']);

            self::updateMasterPalletLastLocation($pallet, $palletData['pallet']['location_id']);

            if (array_key_exists('pallet_details', $palletData)) {
                self::updateOrCreatePalletDetails($pallet, $palletData['pallet_details']);
            }

             if (array_key_exists('pallet_box_details', $palletData)) {
                 self::updateOrCreatePalletBoxDetails($pallet, $palletData['pallet_box_details']);
             }

            if ($palletData['is_request_for_warehouse']) {
                $reachTruckAction = new ReachTruckAction();
                $reachTruckAction->createReachTruckFromPallet($pallet, Warehouse::class);
            }

            if (isset($palletData['is_request_for_loading']) && $palletData['is_request_for_loading']) {
                $reachTruckAction = new ReachTruckAction();
                $reachTruckAction->createReachTruckFromPallet($pallet, Location::class, Location::LOADING_LOCATION_ID);
            }

            if ($pallet->palletDetails->count() == 0 && $pallet->palletBoxDetails->count() == 0) {
                self::persistDeletePallet($pallet);
            }
            return $pallet;
        });
        return $pallet;
    }

    public static function updateMasterPalletLastLocation(Pallet $pallet, int $locationId)
    {
        $lastLocationType = ($pallet->masterPallet->last_locationable_type == Warehouse::class) ? Warehouse::class : Location::class;
        return $pallet->masterPallet->updateLastLocationable($lastLocationType, $locationId);
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

    public static function updateOrCreatePalletBoxDetails(Pallet $pallet, ?array $palletBoxDetails)
    {
        $updatedIds = [];
        if (!empty($palletBoxDetails) && count($palletBoxDetails) > 0) {
            foreach ($palletBoxDetails as $key => $palletBoxDetail) {
                $palletBoxDetailModel = PalletBoxDetails::persistUpdateOrCreatePalletBoxDetails($pallet, $palletBoxDetail);

                if (!empty($palletBoxDetailModel)) {
                    array_push($updatedIds, $palletBoxDetailModel->id);
                }
            }
        } else {
            $updatedIds = [0];
        }

        if (!empty($updatedIds)) {
            PalletBoxDetails::persistDeletePalletBoxDetailsWhereNotInIds($pallet, $updatedIds);
        }
    }

    public function updateLoadingTransferDate(string $transferDate): Pallet
    {
        $this->loading_transfer_date = $transferDate;
        $this->update();

        return $this;
    }

    public static function createPalletBoxDetails(Pallet $pallet, array $palletBoxDetails)
    {
        foreach ($palletBoxDetails as $key => $palletBoxDetail) {
            PalletBoxDetails::persistPalletBoxDetails($pallet, $palletBoxDetail);
        }
    }

    /**
     * @throws Exception
     */
    public static function persistDeletePallet(Pallet $pallet): bool
    {
        $pallet->reachTruck()->delete();
        return $pallet->delete();
    }

    /**
     * @throws Exception
     */
    public function updateLastWarehouseLocation(int $warehouseId)
    {
        $this->warehouse_id = $warehouseId;
        $this->update();

        return;
    }

    public function masterPallet(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(MasterPallet::class);
    }

    public function warehouse(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function palletDetails(): HasMany
    {
        return $this->hasMany(PalletDetails::class);
    }

    public function palletBoxDetails(): HasMany
    {
        return $this->hasMany(PalletBoxDetails::class);
    }

    public function reachTruck(): HasOne
    {
        return $this->hasOne(ReachTruck::class);
    }

    public function orderItemPallet(): HasOne
    {
        return $this->hasOne(OrderItemPallet::class);
    }

    public function order(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public static function boot()
    {
        parent::boot();
        self::observe(PalletObserver::class);
    }

    public function isPalletIsWithBoxDetailsAndPresentAtWarehouse(): bool
    {
        if($this->reachTruck) {
            if($this->reachTruck->from_locationable_type == Location::class) {
                return $this->reachTruck->from_locationable_type == Location::class &&
                    $this->reachTruck->to_locationable_type == Warehouse::class &&
                    $this->reachTruck->is_transfered == true &&
                    $this->palletBoxDetails->count() > 0 &&
                    $this->palletDetails->count() == 0;
            }
        }
        return false;
    }

    /**
     * @return string[]
     */
    public static function getCreateValidationRules()
    {
        return [
            "location_id" => 'required',
            "master_pallet_id" => 'required|exists:master_pallets,id',
            "pallet_details.*.sku_code_id" => 'required|exists:sku_codes,id',
            "pallet_details.*.variant_id" => 'required|exists:variants,id',
            "pallet_details.*.weight" => 'required',
            "pallet_details.*.batch" => 'required',
            "pallet_details.*.batch_date" => 'required',
        ];
    }

    /**
     * @return array
     */
    public static function getCreateValidationRulesForBoxDetails(): array
    {
        return [
            "location_id" => 'required|exists:locations,id',
            "master_pallet_id" => 'required|exists:master_pallets,id',
            "pallet_details.*.box_name" => 'required',
        ];
    }

    /**
     * @return array
     */
    public static function getUpdateValidationRulesForBoxDetails(): array
    {
        return [
            "location_id" => 'required|exists:locations,id',
            "master_pallet_id" => 'required|exists:master_pallets,id'
        ];
    }
}
