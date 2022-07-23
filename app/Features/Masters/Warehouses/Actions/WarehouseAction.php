<?php

namespace App\Features\Masters\Warehouses\Actions;

use Exception;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Collection;
use App\Features\Masters\Warehouses\Domains\Models\Warehouse;

class WarehouseAction
{
    public function getWarehouses(
        ?string $searchValue,
        array $order,
        int $start,
        int $length
    ) {
        $warehouses = Warehouse::search($searchValue)
            ->order($order)
            ->limitBy($start, $length)
            ->get();
        // Modifying total record count and filtered row count as data is manually filtered
        $numberOfTotalRows = Warehouse::count('*');
        if (empty($searchValue)) {
            $numberOfFilteredRows = $numberOfTotalRows;
        } else {
            $numberOfFilteredRows = Warehouse::search($searchValue)
                ->count('*');
        }
        return $this->yajraData($warehouses, $numberOfFilteredRows, $numberOfTotalRows);
    }

    private function yajraData(Collection $warehouses, int $numberOfFilteredRows, int $numberOfTotalRows)
    {
        return DataTables::of($warehouses)
            ->skipPaging()
            ->addColumn('action', function ($warehouse) {
                $action = "<a href='#'
                              title='Edit Warehouse'
                              class='editWarehouse'
                              data-id='" . $warehouse->id . "'
                              data-name='" . $warehouse->name . "'
                              data-href='" . route('warehouses.update', $warehouse->id) . "'
                              data-toggle='modal'
                              data-target='#editWarehouseModal'>
                            <i class='fas fa-edit text-success'></i>
                        </a>";
                if ($warehouse->checkIsEmpty() && $warehouse->id != 1) {
                    $action .= "<a href='javascript:void(0)' title='Delete Warehouse' data-id='{$warehouse->id}' class='deleteWarehouse ml-2'>
                                <i class='fas fa-trash text-danger'></i>
                            </a>";
                }
                return $action;
            })
            ->editColumn('is_empty', function ($warehouse) {
                if ($warehouse->is_empty) {
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

    public function createWarehouse(array $warehouseData)
    {
        foreach ($warehouseData['warehouse'] as $key => $warehouse) {
            Warehouse::persistCreateWarehouse($warehouse);
        }
    }

    public function updateWarehouse(Warehouse $warehouse, array $warehouseData)
    {
        return Warehouse::persistUpdateWarehouse($warehouse, $warehouseData);
    }

    public function deleteWarehouse(Warehouse $warehouse)
    {
        return Warehouse::persistDeleteWarehouse($warehouse);
    }

    public function getEmptyWarehouses()
    {
        return Warehouse::select('id', 'name')->isEmpty()->get();
    }

    public function getWarehouseViaName(string $warehouseName)
    {
        return Warehouse::where('name', $warehouseName)->first();
    }

    public function updateWarehouseAsEmpty(Warehouse $warehouse)
    {
        return Warehouse::updateAsEmpty($warehouse);
    }

    public function updateWarehouseAsNonEmpty(Warehouse $warehouse)
    {
        return Warehouse::updateAsNonEmpty($warehouse);
    }

    public function getEmptyWarehouseByName(?string $name)
    {
        if (!empty($name)) {
            return Warehouse::select('id', 'name as text')->Search($name)->isEmpty()->isActive()->get();
        }

        return;
    }

    public function changeWarehouseState(Warehouse $warehouse, string $currentState)
    {
        return Warehouse::persistUpdateWarehouseState($warehouse, $currentState);
    }

    public function getWarehouseByName(string $name)
    {
        return Warehouse::search($name)->first();
    }
}
