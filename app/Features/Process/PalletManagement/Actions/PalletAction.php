<?php

namespace App\Features\Process\PalletManagement\Actions;

use Carbon\Carbon;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Collection;
use App\Features\Masters\SkuCodes\Domains\Models\SkuCode;
use App\Features\Masters\Variants\Domains\Models\Variant;
use App\Features\Masters\Locations\Domains\Models\Location;
use App\Features\Process\PalletManagement\Domains\Models\Pallet;
use App\Features\Masters\MasterPallet\Domains\Models\MasterPallet;
use App\Features\Masters\Warehouses\Domains\Models\Warehouse;

class PalletAction
{
    public function getMasterData(): array
    {
        $data['masterPallets'] = MasterPallet::isEmpty(true)->get();
        $data['skuCodes'] = SkuCode::all();
        $data['variants'] = Variant::all();
        $data['locations'] = Location::all();
        $data['maxWeightForPallet'] = Pallet::MAX_WEIGHT_FOR_PALLET;
        $data['maxWeightForContainer'] = Pallet::MAX_WEIGHT_FOR_CONTAINER;

        return $data;
    }

    public function getMasterDataForEdit(Pallet $pallet): array
    {
        $data['masterPallets'] = MasterPallet::isEmpty(true)->get();
        $data['masterPallets']->push($pallet->masterPallet);
        $data['skuCodes'] = SkuCode::all();
        $data['variants'] = Variant::all();
        $data['locations'] = Location::all();
        $data['maxWeightForPallet'] = Pallet::MAX_WEIGHT_FOR_PALLET;
        $data['maxWeightForContainer'] = Pallet::MAX_WEIGHT_FOR_CONTAINER;

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

    public function getPallets(
        array $order,
        int $start,
        int $length
    ) {
        $pallets = Pallet::with('masterPallet', 'currentPalletLocation', 'palletDetails', 'updater');

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
                if ($pallet->currentPalletLocation->locationable_type != Warehouse::class) {
                    $action = "<a href='" . route('pallets.edit', $pallet->id) . "' class='editPallet' title='Edit Pallet'>
                            <i class='fas fa-edit text-success'></i>
                        </a>";
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
}
