<?php

namespace App\Features\Reports\Actions;

use App\Features\Masters\Locations\Domains\Models\Location;
use App\Features\Masters\SkuCodes\Domains\Models\SkuCode;
use App\Features\Masters\Variants\Domains\Models\Variant;
use App\Features\Masters\Warehouses\Domains\Models\Warehouse;
use App\Features\OrderManagement\Domains\Models\Order;
use App\Features\OrderManagement\Domains\Models\OrderItem;
use App\Features\OrderManagement\Domains\Models\OrderItemPalletDetails;
use App\Features\Process\PalletManagement\Domains\Models\PalletDetails;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Yajra\DataTables\DataTables;

class SkuReportAction
{
    public function getMasterData(): array
    {
        $data = [];

        $data['skuCodes'] = SkuCode::all();
        $data['variants'] = Variant::all();

        return $data;
    }

    public function getSkuReport(array $filterData)
    {
        $skuCode = SkuCode::find( $filterData['sku_code_id']);
        if(!empty($filterData['variant_id'])) {
            $variants = Variant::has('palletDetails')->orHas('orderItems')
                ->whereHas('orderItems', function ($orderItemQry) {
                    $orderItemQry->whereHas('order', function($order) {
                        $order->where('state', '!=', Order::CANCELLED);
                    })->where('state', '!=', OrderItem::CANCELLED);
                })->where('id', $filterData['variant_id'])->get();
        } else {
            $variants = Variant::has('palletDetails')->orHas('orderItems')
                ->whereHas('orderItems', function ($orderItemQry) {
                    $orderItemQry->whereHas('order', function($order) {
                        $order->where('state', '!=', Order::CANCELLED);
                    })->where('state', '!=', OrderItem::CANCELLED);
                })->get();
        }

        $numberOfTotalRows = $numberOfFilteredRows = $variants->count();
        $collection = collect();

        foreach ($variants as $variant) {
            $data = [];

            // total weight
            $totalWeight = PalletDetails::doesntHave('orderItemPallet')->skuCodeId($skuCode->id)->variantId($variant->id)->sum('weight');

            // total weight in warehouse which are not mapped
            $totalWeightInWarehouse = PalletDetails::doesntHave('orderItemPallet')
                ->skuCodeId($skuCode->id)
                ->variantId($variant->id)
                ->whereHas('pallet.masterPallet', function($qry) {
                    $qry->where('last_locationable_type', Warehouse::class);
                })
                ->sum('weight');

            // total weight in line which are not mapped
            $totalWeightInLine = PalletDetails::doesntHave('orderItemPallet')
                ->skuCodeId($skuCode->id)
                ->variantId($variant->id)
                ->whereHas('pallet.masterPallet', function($qry) {
                    $qry->where('last_locationable_type', Location::class)
                    ->where('last_locationable_id', Location::LOADING_LOCATION_ID);
                })
                ->sum('weight');

            // total weight in locations which are not mapped
            $totalWeightInLocations = PalletDetails::doesntHave('orderItemPallet')
                ->skuCodeId($skuCode->id)
                ->variantId($variant->id)
                ->whereHas('pallet.masterPallet', function($qry) {
                    $qry->where('last_locationable_type', Location::class)
                    ->where('last_locationable_id', '!=', Location::LOADING_LOCATION_ID);
                })
                ->sum('weight');

            // total mapped weight
            $totalMappedWeightData = OrderItem::with('orderItemPalletDetails')->skuCodeId($skuCode->id)->variantId($variant->id)
                ->whereIn('state', [OrderItem::TRANSFERRED, OrderItem::CANCELLED])
                ->whereHas('order', function($orderQry) {
                    $orderQry->whereIn('state', [Order::COMPLETED, Order::CANCELLED]);
                })->get();

            $totalMappedWeight = $totalMappedWeightData->pluck('orderItemPalletDetails')->collapse()->sum('mapped_weight');

            $totalRequiredWeight = OrderItem::skuCodeId($skuCode->id)->variantId($variant->id)
                ->whereIn('state', [OrderItem::TRANSFERRED, OrderItem::CANCELLED])
                ->whereHas('order', function($orderQry) {
                    $orderQry->whereIn('state', [Order::COMPLETED, Order::CANCELLED]);
                })->sum('required_weight');

            $data['sku_code'] = $skuCode->name;
            $data['variant'] = $variant->name;
            $data['total_weight'] = $totalWeight;
            $data['total_weight_in_wh'] = $totalWeightInWarehouse;
            $data['total_weight_in_line'] = $totalWeightInLine;
            $data['total_weight_in_locations'] = $totalWeightInLocations;
            $data['total_mapped_weight'] = $totalMappedWeight;
            $data['total_unmapped_weight'] = $totalRequiredWeight - $totalMappedWeight;

            if (
                $data['total_weight'] == 0 && $data['total_weight_in_wh'] == 0 &&
                $data['total_weight_in_line'] == 0 && $data['total_weight_in_locations'] == 0 &&
                $data['total_mapped_weight'] == 0 && $data['total_unmapped_weight'] == 0
            ) {
                //
            } else {
                $collection->push($data);
            }
        }


        return $this->yajraData($collection, $numberOfFilteredRows, $numberOfTotalRows);
    }

    private function yajraData(Collection $skuCodes, int $numberOfFilteredRows, int $numberOfTotalRows)
    {
        return DataTables::of($skuCodes)
            ->addColumn('sku_code', function ($skuCode) {
                return $skuCode['sku_code'];
            })
            ->addColumn('variant', function ($skuCode) {
                return $skuCode['variant'];
            })
            ->addColumn('total_weight', function ($skuCode) {
                return $skuCode['total_weight'];
            })
            ->addColumn('total_weight_in_wh', function ($skuCode) {
                return $skuCode['total_weight_in_wh'];
            })
            ->addColumn('total_weight_in_line', function ($skuCode) {
                return $skuCode['total_weight_in_line'];
            })
            ->addColumn('total_weight_in_location', function ($skuCode) {
                return $skuCode['total_weight_in_locations'];
            })
            ->addColumn('total_mapped_weight', function ($skuCode) {
                return $skuCode['total_mapped_weight'];
            })
            ->addColumn('total_unmapped_weight', function ($skuCode) {
                return $skuCode['total_unmapped_weight'];
            })
            ->setFilteredRecords($numberOfFilteredRows)
            ->setTotalRecords($numberOfTotalRows)
            ->filter(function () {
                // Implemented in scope
            })
            ->make(true);
    }
}
