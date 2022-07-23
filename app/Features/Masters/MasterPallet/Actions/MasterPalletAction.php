<?php

namespace App\Features\Masters\MasterPallet\Actions;

use Yajra\DataTables\DataTables;
use Illuminate\Support\Collection;
use App\Features\Masters\MasterPallet\Domains\Models\MasterPallet;

class MasterPalletAction
{
    public function getMasterPallets(
        ?string $searchValue,
        array $order,
        int $start,
        int $length
    ) {
        $masterPallets = MasterPallet::search($searchValue);

        // Modifying total record count and filtered row count as data is manually filtered
        $numberOfTotalRows = MasterPallet::count('*');
        if (empty($searchValue)) {
            $numberOfFilteredRows = $numberOfTotalRows;
        } else {
            $numberOfFilteredRows = MasterPallet::search($searchValue)->count('*');
        }

        $masterPallets = $masterPallets->order($order)->limitBy($start, $length)->get();
        return $this->yajraData($masterPallets, $numberOfFilteredRows, $numberOfTotalRows);
    }

    private function yajraData(Collection $masterPallets, int $numberOfFilteredRows, int $numberOfTotalRows)
    {
        return DataTables::of($masterPallets)
            ->skipPaging()
            ->addColumn('action', function ($masterPallet) {
                $action = "<a href='#' data-id='{$masterPallet->id}' data-name='{$masterPallet->name}' data-type='{$masterPallet->type}' class='editMasterPallet' title='Edit Master Pallet'>
                            <i class='fas fa-edit text-success'></i>
                        </a>";
                if ($masterPallet->checkIsEmpty()) {
                    $action .= "<a href='javascript:void(0)' title='Delete Pallet' data-id='{$masterPallet->id}' class='deleteMasterPallet ml-2'>
                                <i class='fas fa-trash text-danger'></i>
                            </a>";
                }
                return $action;
            })
            ->editColumn('is_empty', function ($masterPallet) {
                if ($masterPallet->is_empty) {
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

    public function createMasterPallet(array $masterPalletData)
    {
        return MasterPallet::persistCreateMasterPallet($masterPalletData);
    }

    public function updateMasterPallet(MasterPallet $masterPallet, array $masterPalletData)
    {
        return MasterPallet::persistUpdateMasterPallet($masterPallet, $masterPalletData);
    }

    public function deleteMasterPallet(MasterPallet $masterPallet)
    {
        return MasterPallet::persistDeleteMasterPallet($masterPallet);
    }

    public function getEmptyMatserPallets()
    {
        return MasterPallet::select('id', 'name')->isEmpty()->get();
    }

    public function updateMasterPalletAsEmpty(MasterPallet $masterPallet)
    {
        return $masterPallet->updateIsEmpty(true);
    }

    public function updatePalletAsNonEmpty(MasterPallet $masterPallet)
    {
        return $masterPallet->updateIsEmpty(false);
    }
}
