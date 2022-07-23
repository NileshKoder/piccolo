<?php

namespace App\Features\Masters\Variants\Actions;

use Yajra\DataTables\DataTables;
use Illuminate\Support\Collection;
use App\Features\Masters\Variants\Domains\Models\Variant;

class VariantsAction
{
    public function getVariants(
        ?string $searchValue,
        array $order,
        int $start,
        int $length
    ) {
        $variants = Variant::search($searchValue);

        // Modifying total record count and filtered row count as data is manually filtered
        $numberOfTotalRows = Variant::count('*');
        if (empty($searchValue)) {
            $numberOfFilteredRows = $numberOfTotalRows;
        } else {
            $numberOfFilteredRows = Variant::search($searchValue)
                ->count('*');
        }
        $variants = $variants->order($order)->limitBy($start, $length)->get();
        return $this->yajraData($variants, $numberOfFilteredRows, $numberOfTotalRows);
    }

    private function yajraData(Collection $variants, int $numberOfFilteredRows, int $numberOfTotalRows)
    {
        return DataTables::of($variants)
            ->skipPaging()
            ->addColumn('action', function ($variant) {
                $action = "<a href='#' data-id='{$variant->id}' data-name='{$variant->name}' class='editVariant' title='Edit Sku Code Variant'>
                            <i class='fas fa-edit text-success'></i>
                        </a>";
                return $action;
            })
            ->rawColumns(['action'])
            ->setFilteredRecords($numberOfFilteredRows)
            ->setTotalRecords($numberOfTotalRows)
            ->make(true);
    }

    public function createVariant(array $variantData)
    {
        return Variant::persistCreateVariant($variantData);
    }

    public function updateVariant(array $variantData, Variant $variant)
    {
        return Variant::persistUpdateVariant($variantData, $variant);
    }
}
