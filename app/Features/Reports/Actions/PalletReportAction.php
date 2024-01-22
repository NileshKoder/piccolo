<?php

namespace App\Features\Reports\Actions;

use App\Features\OrderManagement\Domains\Models\Order;
use App\Features\Process\PalletManagement\Domains\Models\PalletBoxDetails;
use Carbon\Carbon;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Collection;
use App\Features\Masters\SkuCodes\Domains\Models\SkuCode;
use App\Features\Masters\Variants\Domains\Models\Variant;
use App\Features\Masters\MasterPallet\Domains\Models\MasterPallet;
use App\Features\Process\PalletManagement\Domains\Models\PalletDetails;

class PalletReportAction
{
    public function getMasterData(): array
    {
        $data['masterPallets'] = MasterPallet::all();
        $data['skuCodes'] = SkuCode::all();
        $data['variants'] = Variant::all();
        $data['orders'] = Order::stateNotIn([Order::TRANSFERRED, Order::CANCELLED, Order::COMPLETED])->get();

        return $data;
    }

    public function getMasterDataForBoxDetails(): array
    {
        $data['masterPallets'] = MasterPallet::all();
        $data['orders'] = Order::stateIn([Order::TRANSFERRED, Order::CANCELLED, Order::COMPLETED])->get();

        return $data;
    }

    public function getPalletReport(array $filerData)
    {
        $palletDetails = PalletDetails::with(
            'pallet.masterPallet.lastLocation',
            'pallet.updater',
            'skuCode',
            'variant',
            'pallet.reachTruck.fromLocationable',
            'orderItemPallet.orderItem.order'
        )
            ->skuCodeId($filerData['sku_code_id'])
            ->variantId($filerData['variant_id'])
            ->masterPalletId($filerData['master_palle_id'])
            ->batchDate($filerData['batch_date'])
            ->orderId($filerData['order_id']);

        // Modifying total record count and filtered row count as data is manually filtered
        $numberOfTotalRows = PalletDetails::count('*');
        if (count($filerData) == 0) {
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
            ->filter(function () {
                // Implemented in scope
            })
            ->make(true);
    }

    public function getBoxPalletReport(array $filerData)
    {
        $palletDetails = PalletBoxDetails::with([
            'pallet'=>function($q) {
                $q->with(['masterPallet.lastLocation', 'updater', 'reachTruck.fromLocationable', 'order']);
            }
        ])
            ->masterPalletId($filerData['master_palle_id'])
            ->orderId($filerData['order_id']);

        // Modifying total record count and filtered row count as data is manually filtered
        $numberOfTotalRows = PalletBoxDetails::count('*');
        if (count($filerData) == 0) {
            $numberOfFilteredRows = $numberOfTotalRows;
        } else {
            $numberOfFilteredRows = $palletDetails->count();
        }

        $palletDetails = $palletDetails->orderBy('id', 'desc')->limitBy($filerData['start'], $filerData['length'])->get();

        return $this->yajraDataForBoxPallet($palletDetails, $numberOfFilteredRows, $numberOfTotalRows);
    }

    private function yajraDataForBoxPallet(Collection $palletDetails, int $numberOfFilteredRows, int $numberOfTotalRows)
    {
        return DataTables::of($palletDetails)
            ->skipPaging()
            ->editColumn('updated_at', function ($pallet) {
                return Carbon::parse($pallet->updated_at)->format('d-m-Y h:i A');
            })
            ->setFilteredRecords($numberOfFilteredRows)
            ->setTotalRecords($numberOfTotalRows)
            ->filter(function () {
                // Implemented in scope
            })
            ->make(true);
    }

    public function getPalletSkuCollectionForExport(array $filerData): Collection
    {
        $palletDetails = PalletDetails::with(
            'pallet.masterPallet.lastLocation',
            'pallet.updater',
            'skuCode',
            'variant',
            'pallet.reachTruck.fromLocationable',
            'orderItemPallet.orderItem.order'
        )
            ->skuCodeId($filerData['sku_code_id'])
            ->variantId($filerData['variant_id'])
            ->masterPalletId($filerData['master_pallet_id'])
            ->batchDate($filerData['batch_date'])
            ->orderId($filerData['order_id'])
            ->get();

        $collection = collect();

        foreach ($palletDetails as $palletDetail) {
            $data = [];

            $data['pallet'] = $palletDetail->pallet->masterPallet->name;
            $data['sku_code'] = $palletDetail->skuCode->name;
            $data['variant'] = $palletDetail->variant->name;
            $data['weight'] = $palletDetail->weight;
            $data['batch'] = $palletDetail->batch;
            $data['last_pickup_location'] = $palletDetail->pallet->reachTruck->fromLocationable->name;
            $data['current_location'] = $palletDetail->pallet->masterPallet->last_locationable->name;
            $data['mapped_order'] = !empty($palletDetail->orderItemPallet) ? $palletDetail->orderItemPallet->orderItem->order->order_number : "";
            $data['last_modified_by'] = $palletDetail->pallet->updater->name;
            $data['last_modified_at'] =  Carbon::parse($palletDetail->pallet->updated_at)->format('d-m-Y h:i A') ;

            $collection->push($data);
        }

        return $collection;
    }

    public function getPalletBoxCollectionForExport(array $filerData): Collection
    {
        $palletDetails = PalletBoxDetails::with([
            'pallet'=>function($q) {
                $q->with(['masterPallet.lastLocation', 'updater', 'reachTruck.fromLocationable', 'order']);
            }
        ])
            ->masterPalletId($filerData['master_pallet_id'])
            ->orderId($filerData['order_id'])
            ->get();

        $collection = collect();

        foreach ($palletDetails as $palletDetail) {
            $data = [];

            $data['pallet'] = $palletDetail->pallet->masterPallet->name;
            $data['box_name'] = $palletDetail->name;
            $data['last_pickup_location'] = $palletDetail->pallet->reachTruck->fromLocationable->name;
            $data['current_location'] = $palletDetail->pallet->masterPallet->last_locationable->name;
            $data['mapped_order'] = !empty($palletDetail->orderItemPallet) ? $palletDetail->orderItemPallet->orderItem->order->order_number : "";
            $data['last_modified_by'] = $palletDetail->pallet->updater->name;
            $data['last_modified_at'] =  Carbon::parse($palletDetail->pallet->updated_at)->format('d-m-Y h:i A') ;

            $collection->push($data);
        }

        return $collection;
    }

    public function getHeaderForSkuExport(): array
    {
        return [
            'Pallet Code',
            'Sku Code',
            'Variant',
            'Weight',
            'Batch',
            'Last PickUp Location',
            'Current Location',
            'Mapped Order',
            'Last Modified By',
            'Last Modified At',
        ];
    }

    public function getHeaderForBoxExport(): array
    {
        return [
            'Pallet Code',
            'Box Name',
            'Last PickUp Location',
            'Current Location',
            'Mapped Order',
            'Last Modified By',
            'Last Modified At',
        ];
    }

    /**
     * @return array
     */
    public function getColumnSizesForExport(): array
    {
        return [
            0 => 'auto',
            1 => 'auto',
            2 => 'auto',
            3 => 'auto',
            4 => 'auto',
            5 => 'auto',
            6 => 'auto',
            7 => 'auto',
            8 => 'auto',
            9 => 'auto',
            10 => 'auto',
            11 => 'auto',
        ];
    }

    /**
     * @return array
     */
    public function getRowStylesForExport(): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
