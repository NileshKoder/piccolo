<?php

namespace App\Features\Reports\Actions;

use Carbon\Carbon;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Collection;
use App\Features\Masters\SkuCodes\Domains\Models\SkuCode;
use App\Features\Masters\Variants\Domains\Models\Variant;
use App\Features\Masters\MasterPallet\Domains\Models\MasterPallet;
use App\Features\Process\PalletManagement\Domains\Models\PalletDetails;

class PalletReportAction
{
    public function getMasterData()
    {
        $data['masterPallets'] = MasterPallet::all();
        $data['skuCodes'] = SkuCode::all();
        $data['variants'] = Variant::all();

        return $data;
    }

    public function getPalletReport(array $filerData)
    {
        $palletDetails = PalletDetails::with(
            'pallet.masterPallet',
            'pallet.updater',
            'skuCode',
            'variant'
        )
            ->skuCodeId($filerData['sku_code_id'])
            ->variantId($filerData['variant_id'])
            ->masterPalletId($filerData['master_palle_id']);

        // Modifying total record count and filtered row count as data is manually filtered
        $numberOfTotalRows = PalletDetails::count('*');
        if (empty($searchValue)) {
            $numberOfFilteredRows = $numberOfTotalRows;
        } else {
            $numberOfFilteredRows = $palletDetails->count();
        }

        $palletDetails = $palletDetails->orderBy('id', 'desc')->limitBy($filerData['start'], $filerData['length'])->get();

        return $this->yajraData($palletDetails, $numberOfFilteredRows, $numberOfTotalRows);
    }

    private function yajraData(Collection $palletDetails, int $numberOfFilteredRows, int $numberOfTotalRows)
    {
        return DataTables::of($palletDetails)
            ->skipPaging()
            ->editColumn('updated_at', function ($pallet) {
                return Carbon::parse($pallet->updated_at)->format('d-m-Y h:i A');
            })
            ->setFilteredRecords($numberOfFilteredRows)
            ->setTotalRecords($numberOfTotalRows)
            ->make(true);
    }
}
