<?php

namespace App\Features\Reports\Routes\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Features\Reports\Actions\PalletReportAction;

class PalletReportController extends Controller
{
    private $palletReportAction;
    public function __construct(PalletReportAction $palletReportAction)
    {
        $this->palletReportAction = $palletReportAction;
    }

    public function index()
    {
        $data = $this->palletReportAction->getMasterData();
        return view('features.reports.pallet.index', compact('data'));
    }

    public function boxDetailReport()
    {
        $data = $this->palletReportAction->getMasterDataForBoxDetails();
        return view('features.reports.pallet.box-details', compact('data'));
    }

    public function getPalletReport(Request $request)
    {
        try {
            $data = $this->palletReportAction->getPalletReport($request->all());
        } catch (Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 500);
        }

        return $data;
    }
    public function getExcel(Request $request)
    {
        # code...
    }

    public function getBoxPalletReport(Request $request)
    {
        try {
            $data = $this->palletReportAction->getBoxPalletReport($request->all());
        } catch (Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 500);
        }

        return $data;
    }
}
