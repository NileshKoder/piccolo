<?php

namespace App\Features\Reports\Routes\Controllers;

use App\Exports\BaseExport;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Features\Reports\Actions\PalletReportAction;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

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
    public function getExcel(Request $request): BinaryFileResponse
    {
        return Excel::download(new BaseExport(
            $this->palletReportAction->getPalletSkuCollectionForExport($request->all()),
            $this->palletReportAction->getHeaderForSkuExport(),
            $this->palletReportAction->getColumnSizesForExport(),
            $this->palletReportAction->getRowStylesForExport()
        ), 'Pallet-Sku-Report-As-On-'. date('d-m-Y-h-i-s') .'.xlsx');
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

    public function getExcelForBox(Request $request): BinaryFileResponse
    {
        return Excel::download(new BaseExport(
            $this->palletReportAction->getPalletBoxCollectionForExport($request->all()),
            $this->palletReportAction->getHeaderForBoxExport(),
            $this->palletReportAction->getColumnSizesForExport(),
            $this->palletReportAction->getRowStylesForExport()
        ), 'Pallet-Box-Report-As-On-'. date('d-m-Y-h-i-s') .'.xlsx');
    }
}
