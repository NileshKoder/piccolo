<?php

namespace App\Features\OrderManagement\Http\Controllers\Api;

use App\Features\OrderManagement\Actions\OrderAction;
use App\Features\OrderManagement\Domains\Models\Order;
use App\Http\Controllers\ApiController;
use Exception;
use Illuminate\Http\Request;

class OrderApiController extends ApiController
{
    private  $orderAction;
    public function __construct(OrderAction $orderAction)
    {
        $this->orderAction = $orderAction;
    }

    public function getTransfferedOrders(Request $request)
    {
        $validate = $this->validation_token($request->token);

        if ($validate !== true) {
            return $validate;
        }

        try {
            $masterData = $this->orderAction->getTransferredOrders();
        } catch (Exception $ex) {
            return $this->errorResponse($ex->getMessage(), 500);
        }

        return $this->showAll($masterData, 200);
    }

    public function updateStateAsComplete(Order $order, Request $request)
    {
        $validate = $this->validation_token($request->token);

        if ($validate !== true) {
            return $validate;
        }

        try {
            $this->orderAction->updateStateToComplete($order);
        } catch (Exception $ex) {
            return $this->errorResponse($ex->getMessage(), 500);
        }

        return $this->showOne(["Order Completed Successfully"], 200);
    }
}
