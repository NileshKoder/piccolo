<?php

namespace App\Features\Masters\Boxes\Actions;

use Yajra\DataTables\DataTables;
use Illuminate\Support\Collection;
use App\Features\Masters\Boxes\Domains\Models\Box;

class BoxAction
{
    public function getBoxes(
        ?string $searchValue,
        array $order,
        int $start,
        int $length
    ) {
        $boxes = Box::search($searchValue);

        // Modifying total record count and filtered row count as data is manually filtered
        $numberOfTotalRows = Box::count();
        if (empty($searchValue)) {
            $numberOfFilteredRows = $numberOfTotalRows;
        } else {
            $numberOfFilteredRows = $boxes->count('*');
        }

        $boxes = $boxes->order($order)->limitBy($start, $length)->get();

        return $this->yajraData($boxes, $numberOfFilteredRows, $numberOfTotalRows);
    }

    private function yajraData(Collection $boxes, int $numberOfFilteredRows, int $numberOfTotalRows)
    {
        return DataTables::of($boxes)
            ->skipPaging()
            ->addColumn('action', function ($box) {
                $action = "<a href='#' data-id='{$box->id}' data-name='{$box->name}' class='editbox' title='Edit Box Code'>
                            <i class='fas fa-edit text-success'></i>
                        </a>";
                if ($box->checkIsEmpty()) {
                    $action .= "<a href='javascript:void(0)' title='Delete Box Code' data-id='{$box->id}' class='deleteBox ml-2'>
                                <i class='fas fa-trash text-danger'></i>
                            </a>";
                }
                return $action;
            })
            ->editColumn('is_empty', function ($box) {
                if ($box->is_empty) {
                    return '<span style="width: 120px;">
                            <span class="badge badge-success font-weight-bold label-lg label-inline">Yes</span>
                        </span>';
                } else {
                    return '<span style="width: 120px;">
                                <span class="badge badge-danger font-weight-bold label-lg label-inline">No</span>
                            </span>';
                }
            })
            ->rawColumns(['is_empty', 'action'])
            ->setFilteredRecords($numberOfFilteredRows)
            ->setTotalRecords($numberOfTotalRows)
            ->make(true);
    }

    public function createBox(array $boxData)
    {
        return Box::persistCreateBox($boxData);
    }

    public function updateBox(Box $box, array $boxData)
    {
        return Box::persistUpdateBox($box, $boxData);
    }

    public function deleteBox(Box $box)
    {
        return Box::persistDeleteBox($box);
    }

    public function getEmptyBoxes()
    {
        return Box::select('id', 'name')->isEmpty()->get();
    }

    public function getBoxViaName(string $boxName)
    {
        return Box::name($boxName)->first();
    }

    public function updateBoxAsEmpty(Box $box)
    {
        return $box->updateIsEmpty(true);
    }

    public function updateCrateCodeAsNonEmpty(Box $box)
    {
        return $box->updateIsEmpty(false);
    }

    public function getEmptyBoxesByName(?string $name)
    {
        if (!empty($name)) {
            return response()->json(Box::select('id', 'name as text')->Search($name)->isEmpty()->isActive()->get());
        }

        return;
    }

    public function getBoxByName(string $name)
    {
        return Box::search($name)->first();
    }
}
