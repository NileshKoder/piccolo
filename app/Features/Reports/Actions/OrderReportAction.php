<?php

namespace App\Features\Reports\Actions;

use App\Features\Masters\SkuCodes\Domains\Models\SkuCode;
use App\Features\Masters\Variants\Domains\Models\Variant;
use App\Features\OrderManagement\Domains\Models\Order;
use App\Features\OrderManagement\Domains\Models\OrderItem;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Yajra\DataTables\DataTables;
use function collect;
use function str_replace;

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

    public function getSkuCollectionForExport(array $filterData): Collection
    {
        $orderItems = OrderItem::with(['order.updator', 'orderItemPalletDetails', 'skuCode', 'variant'])
            ->skuCodeId($filterData['sku_code_id'])
            ->variantId($filterData['variant_id'])
            ->orderId($filterData['order_id'])
            ->pickUpDate($filterData['pickup_date'])
            ->orderState($filterData['order_state'])
            ->stateIn( !empty($filterData['order_item_state']) ? [$filterData['order_item_state']] : [])
            ->get();

        $collection = collect();

        foreach ($orderItems as $orderItem) {
            $data = [];

            $data['order_number'] = $orderItem->order->order_number;
            $data['sku_code'] = $orderItem->skuCode->name;
            $data['variant'] = $orderItem->variant->name;
            $data['weight'] = $orderItem->weight;
            $data['mapped_weight'] = $orderItem->orderItemPalletDetails->sum('mapped_weight');
            $data['pickup_date'] = Carbon::parse($orderItem->pick_up_date)->format('d-m-Y');
            $data['order_status'] = str_replace("_", " ", $orderItem->order->state);
            $data['order_item_status'] = str_replace("_", " ", $orderItem->state);
            $data['last_modified'] = $orderItem->order->updator->name;
            $data['last_modified_at'] = Carbon::parse($orderItem->updated_at)->format('d-m-Y h:i A');

            $collection->push($data);
        }

        return $collection;
    }

    public function getHeaderForExport(): array
    {
        return [
            'Order No',
            'Sku Code',
            'Variant',
            'Weight',
            'Mapped Weight',
            'Pickup Date',
            'Order Status',
            'Order Item Status',
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
