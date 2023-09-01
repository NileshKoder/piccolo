<?php

namespace App\Features\OrderManagement\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Features\OrderManagement\Actions\OrderAction;
use App\Features\OrderManagement\Domains\Models\Order;
use App\Features\OrderManagement\Domains\Models\OrderItem;
use App\Features\OrderManagement\Http\Requests\StoreOrderRequest;
use App\Features\OrderManagement\Http\Requests\UpdateOrderRequest;
use Illuminate\View\View;
use function compact;

class OrderController extends Controller
{
    private $orderAction;

    public function __construct(OrderAction $orderAction)
    {
        $this->orderAction = $orderAction;
    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(): View
    {
        $data = $this->orderAction->getMasterDataIndex();
        return view('features.orders.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(): View
    {
        $masterData = $this->orderAction->getMasterData();
        return view('features.orders.create', compact('masterData'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreOrderRequest  $request
     * @return RedirectResponse
     */
    public function store(StoreOrderRequest $request): RedirectResponse
    {
        try {
            $data = $request->toFormData();
            $this->orderAction->createOrder($data);
        } catch (Exception $ex) {
            return back()->with('error', $ex->getMessage());
        }

        return redirect()->route('orders.index')->with('success', 'Order Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  Order  $order
     * @return void
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Order  $order
     * @return View
     */
    public function edit(Order $order): View
    {
        $masterData = $this->orderAction->getMasterData();
        $order->load('orderItems');

        return view('features.orders.edit', compact('masterData', 'order'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateOrderRequest  $request
     * @param  Order  $order
     * @return RedirectResponse
     */
    public function update(UpdateOrderRequest $request, Order $order): RedirectResponse
    {
        try {
            $data = $request->toFormData();
            $this->orderAction->updateOrder($order, $data);
        } catch (Exception $ex) {
            return back()->with('error', $ex->getMessage());
        }

        return redirect()->route('orders.index')->with('success', 'Order Updated Successfully');
    }

    public function getOrders(Request $request): JsonResponse
    {
        try {
            $data = $this->orderAction->getOrders(
                $request->order,
                $request->start,
                $request->length,
                $request->all()
            );
        } catch (Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 500);
        }
        return $data;
    }

    public function getOrderIteMappedDetails(Order $order, OrderItem $orderItem): View
    {
         $orderItem->load([
            'skuCode', 'variant', 'location', 'orderItemPalletDetails',
            'orderItemPallets.pallet' => function ($q) {
                $q->with('palletDetails', 'masterPallet');
            }
        ]);
        return view('features.orders.order-item-mapped-details', compact('order', 'orderItem'));
    }

    public function unmappedPallet(Request $request)
    {
        try {
            $this->orderAction->unMappedPallet($request->order_item_pallet_id);
        } catch (Exception $ex) {
            return response()->json(['message' => $ex->getMessage(), "code" => 500], 500);
        }

        return response()->json(['message' => "Unmapped successfully", "code" => 200], 200);
    }

    public function updateStateToReadyToMapping(Order $order)
    {
        try {
            $this->orderAction->updateStateToReadyToMapping($order);
        } catch (Exception $ex) {
            return response()->json(['message' => $ex->getMessage(), "code" => 500], 500);
        }

        return response()->json(['message' => "Order state successfully", "code" => 200], 200);
    }

    public function updateStateToComplete(Order $order)
    {
        try {
            $this->orderAction->updateStateToComplete($order);
        } catch (Exception $ex) {
            return response()->json(['message' => $ex->getMessage(), "code" => 500], 500);
        }

        return response()->json(['message' => "Order state successfully", "code" => 200], 200);
    }

    public function updateStateToCancel(Order $order)
    {
        try {
            $this->orderAction->updateStateToCancel($order);
        } catch (Exception $ex) {
            return response()->json(['message' => $ex->getMessage(), "code" => 500], 500);
        }

        return response()->json(['message' => "Order state successfully", "code" => 200], 200);
    }
}
