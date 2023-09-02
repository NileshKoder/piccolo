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
use function strtotime;

class OrderAction
{
    public function getMasterData(): array
    {
        $data['skuCodes'] = SkuCode::get();
        $data['variants'] = Variant::get();
        $data['locations'] = Location::type(Location::LINES)->get();
        $data['showOrderItemDetailsState'] = OrderItem::SHOW_ORDER_ITEM_DETAILS_STATE;
        $data['orderItemCreate'] = OrderItem::CREATED;

        return $data;
    }

    public function getMasterDataIndex(): array
    {
        $data['skuCodes'] = SkuCode::get();
        $data['variants'] = Variant::get();
        $data['locations'] = Location::type(Location::LINES)->get();
        $data['states'] = Order::STATES;

        return $data;
    }

    public function createOrder(array $data): ?Order
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
        int $length,
        array $filterData
    ) {
        $orders = Order::with('orderItems', 'creator', 'updator')
            ->skuCodeId($filterData['sku_code_id'])
            ->variantId($filterData['variant_id'])
            ->locationId($filterData['location_id'])
            ->state($filterData['state'])
            ->pickUpDate($filterData['pickup_date']);

        // Modifying total record count and filtered row count as data is manually filtered
        $numberOfTotalRows = Order::count('*');
        if (empty($searchValue)) {
            $numberOfFilteredRows = $numberOfTotalRows;
        } else {
            $numberOfFilteredRows = $orders->count();
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

                if ($order->state == Order::TRANSFERRED) {
                    $action .= "<a href='javascript:void(0);' data-update_state_route='" . route('orders.updateStateToComplete', $order->id) . "' class='updateStateAsComplete ml-2' title='Change Status to Compete'>
                            <i class='fas fa-flag-checkered text-dark'></i>
                        </a>";
                }

                if ($order->state != Order::COMPLETED && $order->state != Order::CANCELLED) {
                    $action .= "<a href='javascript:void(0);' data-cancel_route='" . route('orders.updateStateToCancel', $order->id) . "' class='updateStateAsCancel ml-2' title='Change Status to Cancel'>
                            <i class='fas fa-ban text-dark'></i>
                        </a>";
                }

                return  $action;
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

    public function updateStateToReadyToMapping(Order $order): Order
    {
        return $order->updateState(Order::READY_TO_MAPPING);
    }

    public function updateStateToComplete(Order $order): Order
    {
        return $order->updateState(Order::COMPLETED);
    }

    public function updateStateToCancel(Order $order): Order
    {
        foreach ($order->orderItems as $orderItem) {
            $orderItem->updateState(OrderItem::CANCELLED);
        }
        return $order->updateState(Order::CANCELLED);
    }

    public  function getTransferredOrders(): Collection
    {
        return Order::select('id', 'order_number')->state(Order::TRANSFERRED)->get();
    }

    public function getOrderStats(): Collection
    {
        $collection = collect();
        foreach (Order::STATES as $state) {
            $data = [];

            $orderByState = Order::state($state)->orderBy('updated_at', 'ASC')->get();
            $data['state'] = $state;
            if($orderByState->count() > 0) {
                $data['count'] = $orderByState->count();
                $data['oldest'] = date('d-M-Y', strtotime($orderByState->first()->updated_at));
            } else {
                $data['count'] = 0;
                $data['oldest'] = '-';
            }

            $collection->push($data);
        }

        return $collection;
    }

    public function getOrdersByPickUpDate(string $pickUpDate): Collection
    {
        $collection = collect();
        $locations = Location::type(Location::LINES)->get();
        foreach ($locations as $location) {
            $data = [];

            $orderItems = OrderItem::locationId($location->id)->pickUpDate($pickUpDate)->get();

            $data['location_id'] = $location->id;
            $data['location_name'] = $location->name;
            $data['count'] = $orderItems->count();
            $data['pickup_date'] = $pickUpDate;

            $collection->push($data);
        }

        return $collection;
    }
}
