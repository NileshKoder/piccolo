<?php

namespace App\Features\OrderManagement\Actions;

use Carbon\Carbon;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Collection;
use App\Features\OrderManagement\Domains\Models\Order;
use App\Features\Masters\SkuCodes\Domains\Models\SkuCode;
use App\Features\Masters\Variants\Domains\Models\Variant;
use App\Features\Masters\Locations\Domains\Models\Location;

class OrderAction
{
    public function getMasterData()
    {
        $data['skuCodes'] = SkuCode::get();
        $data['variants'] = Variant::get();
        $data['locations'] = Location::type(Location::LINES)->get();

        return $data;
    }

    public function createOrder(array $data)
    {
        return Order::persristCreateOrder($data);
    }

    public function getOrders(
        array $order,
        int $start,
        int $length
    ) {
        $orders = Order::with('ordeItemDetails', 'creator', 'updator');

        // Modifying total record count and filtered row count as data is manually filtered
        $numberOfTotalRows = Order::count('*');
        if (empty($searchValue)) {
            $numberOfFilteredRows = $numberOfTotalRows;
        } else {
            $numberOfFilteredRows = Order::count('*');
        }

        $orders = $orders->orderBy('id', 'desc')
            ->limitBy($start, $length)
            ->get();

        return $this->yajraData($orders, $numberOfFilteredRows, $numberOfTotalRows);
    }

    private function yajraData(Collection $orders, int $numberOfFilteredRows, int $numberOfTotalRows)
    {
        return DataTables::of($orders)
            ->skipPaging()
            ->addColumn('action', function ($order) {
                $action = "<a href='" . route('orders.edit', $order->id) . "' class='editOrder' title='Edit Order'>
                            <i class='fas fa-edit text-success'></i>
                        </a>";

                return $action;
            })
            ->editColumn('updated_at', function ($palletCreation) {
                return Carbon::parse($palletCreation->updated_at)->format('d-m-Y h:i A');
            })
            ->rawColumns(['action'])
            ->setFilteredRecords($numberOfFilteredRows)
            ->setTotalRecords($numberOfTotalRows)
            ->make(true);
    }
}