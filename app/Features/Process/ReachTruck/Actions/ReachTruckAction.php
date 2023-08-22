<?php

namespace App\Features\Process\ReachTruck\Actions;

use App\User;
use Carbon\Carbon;
use SebastianBergmann\Diff\Line;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Features\Masters\Locations\Domains\Models\Location;
use App\Features\Masters\Warehouses\Domains\Models\Warehouse;
use App\Features\Process\ReachTruck\Domains\Models\ReachTruck;
use App\Features\Process\PalletManagement\Domains\Models\Pallet;

class ReachTruckAction
{
    public function createReachTruckFromPallet(Pallet $pallet, string $toLocationType, int $toLocationId = null): ?ReachTruck
    {
        $reachTruckData = $this->prepareReachTruckData($pallet, $toLocationType, $toLocationId);
        return ReachTruck::persistReachTruck($reachTruckData, $pallet);
    }

    public function prepareReachTruckData(Pallet $pallet, string $toLocationType, int $toLocationId = null): array
    {
        $reachTruckData = [];
        $reachTruckData['from_locationable_type'] = $pallet->masterPallet->last_locationable_type;
        $reachTruckData['from_locationable_id'] = $pallet->masterPallet->last_locationable_id;
        $reachTruckData['to_locationable_type'] = $toLocationType;
        $reachTruckData['to_locationable_id'] = $toLocationId;
        $reachTruckData['is_transfered'] = false;
        $reachTruckData['transfered_by'] = null;
        $reachTruckData['created_by'] = auth()->user()->id ?? 1;

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
                if ($reachTruck->is_transfered) {
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
                if ($reachTruck->is_transfered) {
                    return '<span style="width: 120px;">
                            <span class="badge badge-success font-weight-bold label-lg label-inline">Yes</span>
                        </span>';
                } else {
                    return '<span style="width: 120px;">
                                <span class="badge badge-danger font-weight-bold label-lg label-inline">No</span>
                            </span>';
                }
            })
            ->rawColumns(['action', 'is_transfer'])
            ->setFilteredRecords($numberOfFilteredRows)
            ->setTotalRecords($numberOfTotalRows)
            ->make(true);
    }

    public function getLocationCount()
    {

        $locations = Location::select(
            'type',
            DB::raw('count(reach_trucks.id) as total')
        )
            ->leftJoin('reach_trucks', function ($rtQuery) {
                $rtQuery->where('from_locationable_type', '=', "App\\Features\\Masters\\Locations\\Domains\\Models\\Location");
                $rtQuery->where('is_transfered', false);
                $rtQuery->on('from_locationable_id', '=', 'locations.id');
            })
            ->groupBy('locations.type')
            ->orderBy('locations.sequence')->get();

        $lineIds = Location::type(Location::LINES)->pluck('id')->toArray();

        foreach ($locations as $location) {
            if($location->type == Location::LINES) {
                $location->type = Location::LOCATION_NAME_CHANGE_LINE;
//                $location->total = ReachTruck::fromLocationableType(Location::class)
//                    ->fromLocationableIdIn($lineIds)
//                    ->notToLocationableType(Location::class)
//                    ->whereIsTransfered(false)
//                    ->count();
            }

            if ($location->type == Location::WH_TO_LINE) {
                $location->type = Location::LOCATION_NAME_CHANGE_WH_TO_LINE;
                $location->total =  ReachTruck::where('from_locationable_type', Warehouse::class)
                    ->where('to_locationable_type', Location::class)
                    ->where('is_transfered', false)
                    ->count();
            }

            if($location->type == Location::LOCATION_NAME_CHANGE_LINE_TO_WH) {
                 $location->total = ReachTruck::fromLocationableType(Location::class)
                    ->toLocationableType(Warehouse::class)
                    ->toLocationableIdIn([Location::LOADING_LOCATION_ID])
                    ->nonTransfered()
                    ->count();
            }

            if($location->type == Location::LINE_TO_LOADING) {
                $location->type = Location::LOCATION_NAME_CHANGE_LINE_TO_LOADING;
                 $location->total = ReachTruck::fromLocationableType(Location::class)
                     ->fromLocationableIdIn($lineIds)
                     ->toLocationableType(Location::class)
                     ->toLocationableId(Location::LOADING_LOCATION_ID)
                     ->nonTransfered()
                     ->count();
            }

            if ($location->type == Location::LOADING) {
                $location->type = Location::LOCATION_NAME_CHANGE_LOADING;
            }

            $location->type = str_replace("_", " ", $location->type);
        }
        // dd($locations);
        return $locations;
    }

    public function getCreateData(string $locationType)
    {
        $data['reachTruckUsers'] = User::role(User::REACH_TRUCK)->get();
        $data['reachTrucks'] = $this->getNonTransferedPalletsFromReachTruckFromLocationableType($locationType);

        if ($locationType == "WH TO ASSEMBLY LINE") {
            $warehouseIds = ReachTruck::fromLocationableType(Warehouse::class)->nonTransfered()->pluck('from_locationable_id')->toArray();
            $data['fromLocations'] = Warehouse::idIn($warehouseIds)->get();
            $data['fromLocationType'] = Warehouse::class;
            $data['toLocationType'] = Location::class;
            $data['toLocations'] = Location::type(Location::LINES)->get();
        } elseif($locationType == str_replace("_", " ", Location::LOCATION_NAME_CHANGE_LINE_TO_WH)) {
            $data['fromLocations'] = Location::type(Location::LINES)->get();
            $data['fromLocationType'] = Location::class;
            $data['toLocationType'] = Location::class;
            $data['toLocations'] = Location::type(Location::LOADING)->get();
        } elseif($locationType == str_replace("_", " ", Location::LINE_TO_LOADING)) {
            $data['fromLocations'] = Location::type(Location::LINES)->get();
            $data['fromLocationType'] = Location::class;
            $data['toLocationType'] = Location::class;
            $data['toLocations'] = Location::type(Location::LOADING)->get();
        } else {
            if($locationType == Location::LOCATION_NAME_CHANGE_LINE)
            {
                $locationType = Location::LINES;
            }

            $data['fromLocations'] = Location::type($locationType)->get();
            $data['fromLocationType'] = Location::class;
            $data['toLocationType'] = Warehouse::class;
            $data['toLocations'] = Warehouse::select(['id','name'])->isEmpty()->get();
        }

        return $data;
    }

    public function getEditData(ReachTruck $reachTruck)
    {

        if ($reachTruck->from_locationable_type == Location::class) {
            if($reachTruck->to_locationable_type != Location::class) {
                $data['reachTrucks'] = $this->getNonTransferedPalletsFromReachTruckFromLocationableType($reachTruck->fromLocationable->type);
                $data['reachTrucks']->push($reachTruck);

                $data['fromLocations'] = Location::type($reachTruck->fromLocationable->type)->get();
                $data['fromLocationType'] = Location::class;

                $data['toLocationType'] = Warehouse::class;
                $data['toLocations'] = Warehouse::isEmpty()->get();
                $data['toLocations']->push($reachTruck->toLocationable);
            } else {
                $data['reachTrucks'] = $this->getNonTransferedPalletsFromReachTruckFromLocationableType($reachTruck->fromLocationable->type);
                $data['reachTrucks']->push($reachTruck);
                $data['fromLocations'] = Location::type($reachTruck->fromLocationable->type)->get();
                $data['fromLocationType'] = Location::class;

                $data['toLocationType'] =Location::class;
                $data['toLocations'] = Location::type(Location::LOADING)->get();
            }
        } else if($reachTruck->from_locationable_type == Warehouse::class) {
            $data['reachTrucks'] = collect();
            $data['reachTrucks']->push($reachTruck);
            $warehouseIds = ReachTruck::fromLocationableType(Warehouse::class)->nonTransfered()->pluck('from_locationable_id')->toArray();
            array_push($warehouseIds, $reachTruck->from_locationable_id);
            $data['fromLocations'] = Warehouse::idIn($warehouseIds)->get();
            $data['fromLocationType'] = Warehouse::class;
            $data['toLocationType'] = Location::class;
            $data['toLocations'] = Location::type(Location::LINES)->get();
        }

        $data['reachTruckUsers'] = User::role(User::REACH_TRUCK)->get();

        return $data;
    }

    public function getPalletForReachTruck(array $requestData)
    {
        return ReachTruck::with('pallet.masterPallet')
            ->fromLocationAbleType($requestData['from_locationable_type'])
            ->fromLocationAbleId($requestData['from_locationable_id'])
            ->nonTransfered()
            ->get();
    }

    public function getNonTransferedPalletsFromLocationable(string $locationType)
    {
        return Pallet::with('masterPallet')
            ->currentLocationType($locationType)
            ->nonTransfered()
            ->get();
    }

    public function getNonTransferedPalletsFromReachTruckFromLocationableType(string $locationType)
    {
        return ReachTruck::with('pallet.masterPallet')
            ->fromLocationAbleType($locationType)
            ->nonTransfered()
            ->get();
    }

    public function processTransferPallet(array $reachTruckData)
    {
        return ReachTruck::persitProcessPalletTransfer($reachTruckData);
    }

    public function processUpdateTransferPalletDetails(array $reachTruckData, ReachTruck $reachTruck)
    {
        return ReachTruck::persitUpdatePalletTransferDeatils($reachTruckData, $reachTruck);
    }
}
