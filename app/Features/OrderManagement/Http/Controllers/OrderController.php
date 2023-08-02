<?php

namespace App\Features\OrderManagement\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Features\OrderManagement\Actions\OrderAction;
use App\Features\OrderManagement\Domains\Models\Order;
use App\Features\OrderManagement\Domains\Models\OrderItem;
use App\Features\OrderManagement\Http\Requests\StoreOrderRequest;
use App\Features\OrderManagement\Http\Requests\UpdateOrderRequest;

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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('features.orders.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $masterData = $this->orderAction->getMasterData();
        return view('features.orders.create', compact('masterData'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreOrderRequest $request)
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
     * @param  \App\Features\OrderManagement\Domains\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Features\OrderManagement\Domains\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        $masterData = $this->orderAction->getMasterData();
        $order->load('ordeItems');

        return view('features.orders.edit', compact('masterData', 'order'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Features\OrderManagement\Domains\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateOrderRequest $request, Order $order)
    {
        try {
            $data = $request->toFormData();
            $this->orderAction->updateOrder($order, $data);
        } catch (Exception $ex) {
            return back()->with('error', $ex->getMessage());
        }

        return redirect()->route('orders.index')->with('success', 'Order Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Features\OrderManagement\Domains\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        //
    }

    public function getOrders(Request $request)
    {
        try {
            $data = $this->orderAction->getOrders(
                $request->order,
                $request->start,
                $request->length
            );
        } catch (Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 500);
        }
        return $data;
    }

    public function getOrderIteMappedDetails(Order $order, OrderItem $orderItem)
    {
        if ($orderItem->state == OrderItem::TRANSFERED) {
            abort(403, 'This Item Is Already Transfered');
        }

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
}
