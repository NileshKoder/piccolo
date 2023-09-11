<?php

namespace App\Features\Reports\Actions;

use App\Features\Masters\SkuCodes\Domains\Models\SkuCode;
use App\Features\Masters\Variants\Domains\Models\Variant;
use App\Features\OrderManagement\Domains\Models\Order;
use App\Features\OrderManagement\Domains\Models\OrderItem;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Yajra\DataTables\DataTables;

class OrderReportAction
{
    public function getMasterData(): array
    {
        $data = [];

        $data['orders'] = Order::all();
        $data['skuCodes'] = SkuCode::all();
        $data['variants'] = Variant::all();
        $data['orderStates'] = Order::STATES;
        $data['orderItemStates'] = OrderItem::STATES;

        return $data;
    }

    public function getOrderReport(array $filterData)
    {
        $orderItems = OrderItem::with(['order.updator', 'orderItemPalletDetails', 'skuCode', 'variant'])
            ->skuCodeId($filterData['sku_code_id'])
            ->variantId($filterData['variant_id'])
            ->orderId($filterData['order_id'])
            ->pickUpDate($filterData['pickup_date'])
            ->orderState($filterData['order_state'])
            ->stateIn( !empty($filterData['order_item_state']) ? [$filterData['order_item_state']] : []);

        // Modifying total record count and filtered row count as data is manually filtered
        $numberOfTotalRows = OrderItem::count('*');
        if (count($filterData) == 0) {
            $numberOfFilteredRows = $numberOfTotalRows;
        } else {
            $numberOfFilteredRows = $orderItems->count();
        }

        $orderItems = $orderItems->orderBy('id', 'desc')->limitBy($filterData['start'], $filterData['length'])->get();

        return $this->yajraData($orderItems, $numberOfFilteredRows, $numberOfTotalRows);
    }

    private function yajraData(Collection $orderItems, int $numberOfFilteredRows, int $numberOfTotalRows)
    {
        return DataTables::of($orderItems)
            ->editColumn('order.updated_at', function ($orderItem) {
                return Carbon::parse($orderItem->updated_at)->format('d-m-Y h:i A');
            })
            ->editColumn('pick_up_date', function ($orderItem) {
                return Carbon::parse($orderItem->pick_up_date)->format('d-m-Y');
            })
            ->addColumn('mapped_weight', function ($orderItem) {
                return $orderItem->orderItemPalletDetails->sum('mapped_weight');
            })
            ->setFilteredRecords($numberOfFilteredRows)
            ->setTotalRecords($numberOfTotalRows)
            ->filter(function () {
                // Implemented in scope
            })
            ->make(true);
    }
}
