<?php

namespace App\Features\Process\PalletManagement\Actions;

use App\Features\OrderManagement\Domains\Models\Order;
use App\Features\Process\ReachTruck\Domains\Models\ReachTruck;
use Carbon\Carbon;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Collection;
use App\Features\Masters\SkuCodes\Domains\Models\SkuCode;
use App\Features\Masters\Variants\Domains\Models\Variant;
use App\Features\Masters\Locations\Domains\Models\Location;
use App\Features\Masters\Warehouses\Domains\Models\Warehouse;
use App\Features\Process\ReachTruck\Actions\ReachTruckAction;
use App\Features\Process\PalletManagement\Domains\Models\Pallet;
use App\Features\Masters\MasterPallet\Domains\Models\MasterPallet;
use function strtotime;

class PalletAction
{
    public function getMasterData(): array
    {
        $data['masterPallets'] = MasterPallet::select('id', 'name')->isEmpty(true)->get();
        $data['skuCodes'] = SkuCode::select('id', 'name')->get();
        $data['variants'] = Variant::select('id', 'name')->get();
        $data['locations'] = Location::select('id', 'name', 'abbr')
            ->typeIn([Location::GLASS, Location::CERAMIC, Location::RECYCLE])
            ->orderBy('id', 'ASC')->get();
        $data['maxWeightForPallet'] = Pallet::MAX_WEIGHT_FOR_PALLET;
        $data['maxWeightForContainer'] = Pallet::MAX_WEIGHT_FOR_CONTAINER;

        return $data;
    }

    public function getMasterDataForReturn(): array
    {
        $data['masterPallets'] = MasterPallet::select('id', 'name')
            ->whereHasMorph('lastLocation', [Location::class], function ($q) {
                $q->whereIn('last_locationable_id', Location::type(Location::LINES)->pluck('id')->toArray());
            })
            ->isEmpty(false)->get();
        $data['skuCodes'] = SkuCode::select('id', 'name')->get();
        $data['variants'] = Variant::select('id', 'name')->get();
        $data['locations'] = Location::select('id', 'name', 'abbr')
            ->whereHas('toLocationable', function ($q) {
                return $q->transfered();
            })
            ->typeIn([Location::LINES])
            ->orderBy('id', 'ASC')->get();
        $data['maxWeightForPallet'] = Pallet::MAX_WEIGHT_FOR_PALLET;
        $data['maxWeightForContainer'] = Pallet::MAX_WEIGHT_FOR_CONTAINER;

        return $data;
    }

    public function getMasterDataForEdit(Pallet $pallet): array
    {
        $data['masterPallets'] = MasterPallet::select('id', 'name')->isEmpty(true)->get();
        $data['masterPallets']->push($pallet->masterPallet);
        $data['skuCodes'] = SkuCode::select('id', 'name')->get();
        $data['variants'] = Variant::select('id', 'name')->get();
        $data['locations'] = Location::select('id', 'name', 'abbr')->orderBy('id', 'ASC')->get();
        $data['maxWeightForPallet'] = Pallet::MAX_WEIGHT_FOR_PALLET;
        $data['maxWeightForContainer'] = Pallet::MAX_WEIGHT_FOR_CONTAINER;

        return $data;
    }

    public function getMasterDataIndex(): array
    {
        $data['masterPallets'] = MasterPallet::select('id', 'name')->isEmpty(false)->get();
        $data['skuCodes'] = SkuCode::select('id', 'name')->get();
        $data['variants'] = Variant::select('id', 'name')->get();
        $data['locations'] = Location::select('id', 'name', 'abbr')->orderBy('id', 'ASC')->onlyActive()->get();
        $data['orders'] = Order::select('id', 'order_number')->has('pallets')->stateIn([Order::COMPLETED, Order::TRANSFERRED])->get();

        return $data;
    }

    public function createPallet(array $palletData): ?Pallet
    {
        return Pallet::persistCreatePallet($palletData);
    }

    public function updatePallet(Pallet $pallet, array $palletData): ?Pallet
    {
        return Pallet::persistUpdatePallet($pallet, $palletData);
    }

    public function  setDateForPalleTransferAtLoading(int $palletId, string $transferDate)
    {
        $pallet = Pallet::findOrFail($palletId);
        $pallet->updateLoadingTransferDate($transferDate);
    }

    public function getPallets(
        array $order,
        int $start,
        int $length,
        array $filterData
    ) {
        $pallets = Pallet::with('masterPallet.lastLocation', 'palletDetails', 'updater', 'order')
            ->skuCodeId($filterData['sku_code_id'])
            ->variantId($filterData['variant_id'])
            ->masterPalletId($filterData['master_pallet_id'])
            ->orderId($filterData['order_id'])
            ->locationId($filterData['location_id']);

        // Modifying total record count and filtered row count as data is manually filtered
        $numberOfTotalRows = Pallet::count('*');
        if (empty($searchValue)) {
            $numberOfFilteredRows = $numberOfTotalRows;
        } else {
            $numberOfFilteredRows = $pallets->count();
        }

        $pallets = $pallets->orderBy('id', 'desc')->limitBy($start, $length)->get();

        return $this->yajraData($pallets, $numberOfFilteredRows, $numberOfTotalRows);
    }

    private function yajraData(Collection $pallets, int $numberOfFilteredRows, int $numberOfTotalRows)
    {
        return DataTables::of($pallets)
            ->skipPaging()
            ->addColumn('action', function ($pallet) {
                $action = "";
                if ($pallet->masterPallet->last_locationable_type != Warehouse::class) {
                    $routeName = '#';
                    if($pallet->palletDetails->count() > 0)
                    {
                        $routeName = route('pallets.edit', $pallet->id);
                    } elseif ($pallet->palletBoxDetails->count() > 0) {
                        $routeName = route('pallets.edit.box-details', $pallet->id);
                    }

                    $action .= "<a href='" . $routeName . "' class='editPallet' title='Edit Pallet'>
                            <i class='fas fa-edit text-success'></i>
                        </a>";


                } else {
                    if($pallet->isPalletIsWithBoxDetailsAndPresentAtWarehouse()) {
                        $action .= "<a href='javascript:void(0);' class='setDateForLoading' title='Set Date For Loading'
                                        data-pallet_id='". $pallet->id ."'
                                        data-pallet-name='". $pallet->masterPallet->name ."'
                                        data-loading_transfer_date='". date('d-m-Y', strtotime($pallet->loading_transfer_date)) ."'>
                            <i class='fas fa-calendar-times text-danger'></i>
                        </a>";
                    }
                }
                return $action;
            })
            ->editColumn('updated_at', function ($pallet) {
                return Carbon::parse($pallet->updated_at)->format('d-m-Y h:i A');
            })
            ->rawColumns(['action'])
            ->setFilteredRecords($numberOfFilteredRows)
            ->setTotalRecords($numberOfTotalRows)
            ->make(true);
    }

    public function getMastersForBoxDetails()
    {
        $data = [];

        $data['locations'] = Location::select('id', 'name', 'abbr')->type(Location::LINES)->get();
        $data['masterPallets'] = MasterPallet::select('id', 'name')->isEmpty(true)->get();
        $data['orders'] = Order::select(['id', 'order_number'])->StateIn([Order::TRANSFERRED, Order::COMPLETED])->get();

        return $data;
    }

    public function getMastersForBoxDetailsForEdit(Pallet  $pallet): array
    {
        $data = [];
        $data['masterPallets'] = MasterPallet::select('id', 'name')->isEmpty(true)->get();
        $data['masterPallets']->push($pallet->masterPallet);
        $data['locations'] = Location::select(['id', 'name', 'abbr'])->type(Location::LINES)->get();
        $data['orders'] = Order::StateIn([Order::TRANSFERRED])->get();
        if(!empty($pallet->order)) {
            if($pallet->order->state == Order::COMPLETED) {
                $data['orders']->push($pallet->order);
            }
        }
        return $data;
    }
}
