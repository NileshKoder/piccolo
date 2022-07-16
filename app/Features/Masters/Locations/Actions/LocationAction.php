<?php

namespace App\Features\Masters\Locations\Actions;

use Yajra\DataTables\DataTables;
use Illuminate\Support\Collection;
use App\Features\Masters\SkuCodes\Domains\Models\SkuCode;

class SkuCodeAction
{
    public function getSkuCodes(
        ?string $searchValue,
        array $order,
        int $start,
        int $length
    ) {
        $skuCodes = SkuCode::search($searchValue)
            ->order($order)
            ->limitBy($start, $length)
            ->get();
        // Modifying total record count and filtered row count as data is manually filtered
        $numberOfTotalRows = SkuCode::count('*');
        if (empty($searchValue)) {
            $numberOfFilteredRows = $numberOfTotalRows;
        } else {
            $numberOfFilteredRows = SkuCode::search($searchValue)
                ->count('*');
        }
        return $this->yajraData($skuCodes, $numberOfFilteredRows, $numberOfTotalRows);
    }

    private function yajraData(Collection $skuCodes, int $numberOfFilteredRows, int $numberOfTotalRows)
    {
        return DataTables::of($skuCodes)
            ->skipPaging()
            ->addColumn('action', function ($skuCode) {
                $action = "<a href='#' data-id='{$skuCode->id}' data-name='{$skuCode->name}' data-type='{$skuCode->type}' class='editSkuCode' title='Edit Sku Code'>
                            <i class='fas fa-edit text-success'></i>
                        </a>";

                $action .= "<a href='" . route('sku-codes.sku-code-variants.create', $skuCode->id) . "' class='ml-2' title='Add Sku Code Variants'>
                            <i class='fas fa-plus text-warning'></i>
                        </a>";
                return $action;
            })
            ->rawColumns(['action'])
            ->setFilteredRecords($numberOfFilteredRows)
            ->setTotalRecords($numberOfTotalRows)
            ->make(true);
    }

    public function createSkuCode(array $skuCodeData)
    {
        return SkuCode::persistCreateSkuCode($skuCodeData);
    }

    public function updateSkuCode(SkuCode $skuCode, array $skuCodeData)
    {
        return SkuCode::persistUpdateSkuCode($skuCode, $skuCodeData);
    }
}
