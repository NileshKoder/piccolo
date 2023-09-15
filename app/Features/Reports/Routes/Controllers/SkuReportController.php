<?php

namespace App\Features\Reports\Routes\Controllers;

use App\Exports\BaseExport;
use App\Features\Reports\Actions\SkuReportAction;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class SkuReportController extends Controller
{
    private $skuReportAction;
    public function __construct(SkuReportAction $skuReportAction)
    {
        $this->skuReportAction = $skuReportAction;
    }
    public function index()
    {
        $data = $this->skuReportAction->getMasterData();
        return view('features.reports.sku.index', compact('data'));
    }

    public function getSkuReport(Request $request)
    {
        try {
            if(empty($request->sku_code_id)) {
                throw new Exception("Sku code is required");
            }
            $data = $this->skuReportAction->getSkuReport($request->all());
        } catch (Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 500);
        }

        return $data;
    }

    public function getExcel(Request $request)
    {
        return Excel::download(new BaseExport(
            $this->skuReportAction->getSkuCollectionForExport($request->all()),
            $this->skuReportAction->getHeaderForExport(),
            $this->skuReportAction->getColumnSizesForExport(),
            $this->skuReportAction->getRowStylesForExport(),
        ), 'Sku-Report-As-On-'. date('d-m-Y-h-i-s') .'.xlsx');
    }
}
