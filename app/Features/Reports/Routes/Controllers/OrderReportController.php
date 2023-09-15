<?php

namespace App\Features\Reports\Routes\Controllers;

use App\Exports\BaseExport;
use App\Features\Reports\Actions\OrderReportAction;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

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

    public function getExcel(Request $request): BinaryFileResponse
    {
        return Excel::download(new BaseExport(
            $this->orderReportAction->getSkuCollectionForExport($request->all()),
            $this->orderReportAction->getHeaderForExport(),
            $this->orderReportAction->getColumnSizesForExport(),
            $this->orderReportAction->getRowStylesForExport()
        ), 'Order-Report-As-On-'. date('d-m-Y-h-i-s') .'.xlsx');
    }
}
