<?php

namespace App\Features\Reports\Routes\Controllers;

use App\Features\Reports\Actions\OrderReportAction;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;

class OrderReportController extends Controller
{
    private $orderReportAction;
    public function __construct(OrderReportAction $orderReportAction)
    {
        $this->orderReportAction = $orderReportAction;
    }
    public function index()
    {
        $data = $this->orderReportAction->getMasterData();
        return view('features.reports.order.index', compact('data'));
    }

    public function getOrderReport(Request $request)
    {
        try {
            $data = $this->orderReportAction->getOrderReport($request->all());
        } catch (Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 500);
        }

        return $data;
    }
}
