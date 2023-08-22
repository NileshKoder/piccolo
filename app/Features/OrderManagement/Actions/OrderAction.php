<?php

namespace App\Features\OrderManagement\Actions;

use Carbon\Carbon;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Collection;
use App\Features\OrderManagement\Domains\Models\Order;
use App\Features\Masters\SkuCodes\Domains\Models\SkuCode;
use App\Features\Masters\Variants\Domains\Models\Variant;
use App\Features\Masters\Locations\Domains\Models\Location;
use App\Features\OrderManagement\Domains\Models\OrderItem;
use App\Features\OrderManagement\Domains\Models\OrderItemPallet;
use function route;

class OrderAction
{
    public function getMasterData()
    {
        $data['skuCodes'] = SkuCode::get();
        $data['variants'] = Variant::get();
        $data['locations'] = Location::type(Location::LINES)->get();
        $data['orderItemStates'] = OrderItem::STATES;
        $data['orderItemCreate'] = OrderItem::CREATED;
        $data['orderItemPartialMapped'] = OrderItem::PARTIAL_MAPPED;
        $data['orderItemMapped'] = OrderItem::MAPPED;
        $data['orderItemTransffered'] = OrderItem::TRANSFERRED;

        return $data;
    }

    public function createOrder(array $data)
    {
        return Order::persistCreateOrder($data);
    }

    public function updateOrder(Order $order, array $data)
    {
        return Order::persistUpdateOrder($order, $data);
    }

    public function getOrders(
        array $order,
        int $start,
        int $length
    ) {
        $orders = Order::with('orderItems', 'creator', 'updator');

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
                if($order->isOrderHasAllDetails() && $order->state == Order::DRAFT) {
                    $action .= "<a href='javascript:void(0);' data-update_state_route='" . route('orders.updateStateToReadyToDispatch', $order->id) . "' class='updateState ml-2' title='Change Status to READY TO MAPPING'>
                            <i class='fas fa-check text-warning'></i>
                        </a>";
                }

                if ($order->state == Order::TRANSFERRING_PALLETS) {
                    $action .= "<a href='javascript:void(0);' data-update_state_route='" . route('orders.updateStateToComplete', $order->id) . "' class='updateStateAsComplete ml-2' title='Change Status to Compete'>
                            <i class='fas fa-flag-checkered text-dark'></i>
                        </a>";
                }


                return $action;
            })
            ->editColumn('state', function($order) {
                return str_replace("_", " ", $order->state);
            })
            ->editColumn('updated_at', function ($order) {
                return Carbon::parse($order->updated_at)->format('d-m-Y h:i A');
            })
            ->rawColumns(['action'])
            ->setFilteredRecords($numberOfFilteredRows)
            ->setTotalRecords($numberOfTotalRows)
            ->make(true);
    }

    public function unMappedPallet(int $orderItemPalletId)
    {
        $orderItemPallet = OrderItemPallet::find($orderItemPalletId);
        return $orderItemPallet->unMappedPallet();
    }

    public function updateStateToReadyToMapping(Order $order)
    {
        return $order->updateState(Order::READY_TO_MAPPING);
    }

    public function updateStateToComplete(Order $order)
    {
        return $order->updateState(Order::COMPLETED);
    }
}
