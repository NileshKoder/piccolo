<?php

namespace App\Features\Process\ReachTruck\Actions;

use App\User;
use Carbon\Carbon;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Features\Masters\Locations\Domains\Models\Location;
use App\Features\Masters\Warehouses\Domains\Models\Warehouse;
use App\Features\Process\ReachTruck\Domains\Models\ReachTruck;
use App\Features\Process\PalletManagement\Domains\Models\Pallet;

class ReachTruckAction
{
    public function createReachTruckFromPallet(Pallet $pallet): ?ReachTruck
    {
        $reachTruckData = $this->prepareReachTruckData($pallet);
        return ReachTruck::persistReachTruck($reachTruckData, $pallet);
    }

    public function prepareReachTruckData(Pallet $pallet): array
    {
        $reachTruckData = [];
        $reachTruckData['from_locationable_type'] = $pallet->currentPalletLocation->locationable_type;
        $reachTruckData['from_locationable_id'] = $pallet->currentPalletLocation->locationable_id;
        $reachTruckData['to_locationable_type'] = Warehouse::class;
        $reachTruckData['to_locationable_id'] = null;
        $reachTruckData['is_transfered'] = false;
        $reachTruckData['created_by'] = auth()->user()->id;

        return $reachTruckData;
    }

    public function getReachTrucks(array $order, int $start, int $length)
    {
        $reachTruck = ReachTruck::with('fromLocationable', 'toLocationable', 'pallet.masterPallet', 'transferBy', 'creator');

        // Modifying total record count and filtered row count as data is manually filtered
        $numberOfTotalRows = ReachTruck::count('*');
        if (empty($searchValue)) {
            $numberOfFilteredRows = $numberOfTotalRows;
        } else {
            $numberOfFilteredRows = ReachTruck::count('*');
        }

        $reachTruck = $reachTruck->orderBy('id', 'desc')->limitBy($start, $length)->get();
        return $this->yajraData($reachTruck, $numberOfFilteredRows, $numberOfTotalRows);
    }

    private function yajraData(Collection $reachTrucks, int $numberOfFilteredRows, int $numberOfTotalRows)
    {
        return DataTables::of($reachTrucks)
            ->skipPaging()
            ->addColumn('action', function ($reachTruck) {
                $action = '';
                if (($reachTruck->is_transfered)) {
                    $action = "<a href='" . route('reach-trucks.edit', $reachTruck->id) . "' class='editReachTruck' title='Edit Reach Truck'>
                                    <i class='fas fa-edit text-success'></i>
                                </a>";
                }

                // $action .= "<a href='javascript:void(0)' data-id='{$reachTruck->id}' class='ml-2 deleteReachTruck' title='Delete Reach Truck'>
                //             <i class='fas fa-trash text-danger'></i>
                //         </a>";
                return $action;
            })
            ->editColumn('updated_at', function ($reachTruck) {
                return Carbon::parse($reachTruck->updated_at)->format('d-m-Y h:i A');
            })
            ->editColumn('is_transfer', function ($reachTruck) {
                return ($reachTruck->is_transfered) ? "Yes" : "No";
            })
            ->rawColumns(['action'])
            ->setFilteredRecords($numberOfFilteredRows)
            ->setTotalRecords($numberOfTotalRows)
            ->make(true);
    }

    public function getLocationCount()
    {
        return Location::select(
            'type',
            DB::raw('count(reach_trucks.id) as total')
        )
            ->leftJoin('reach_trucks', function ($rtQuery) {
                $rtQuery->where('from_locationable_type', '=', "App\\Features\\Masters\\Locations\\Domains\\Models\\Location");
                $rtQuery->on('from_locationable_id', '=', 'locations.id');
            })
            ->groupBy('locations.type')->get();
    }

    public function getCreateData(string $locationType)
    {
        $data['reachTruckUsers'] = User::role(User::REACH_TRUCK)->get();
        $data['fromLocations'] = Location::type($locationType)->get();
        $data['fromLocationType'] = Location::class;
        $data['toLocationType'] = Warehouse::class;
        $data['toLocations'] = Warehouse::isEmpty()->get();

        return $data;
    }

    public function getPalletForReachTruck(array $requestData)
    {
        if ($requestData['from_locationable_type'] == Warehouse::class) {
            //
        } else {
            return ReachTruck::with('pallet.masterPallet')
                ->fromLocationAbleType($requestData['from_locationable_type'])
                ->fromLocationAbleId($requestData['from_locationable_id'])
                ->nonTransfered()
                ->get();
        }
    }
}
