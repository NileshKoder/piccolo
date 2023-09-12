<?php

namespace App\Features\Reports\Routes\Controllers;

use App\Features\Reports\Actions\OrderReportAction;
use App\Features\Reports\Actions\SkuReportAction;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;

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
            $data = $this->skuReportAction->getSkuReport($request->all());
        } catch (Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 500);
        }

        return $data;
    }
}
