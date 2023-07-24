<?php

namespace App\Features\Process\ReachTruck\Http\Controllers\Api;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Validator;
use App\Features\Masters\Locations\Domains\Models\Location;
use App\Features\Process\ReachTruck\Actions\ReachTruckAction;
use App\Features\Process\ReachTruck\Domains\Models\ReachTruck;

class ReachTruckApiController extends ApiController
{
    private $reachTruckAction;

    public function __construct(ReachTruckAction $reachTruckAction)
    {
        $this->reachTruckAction = $reachTruckAction;
    }

    public function home(Request $request)
    {
        $validate = $this->validation_token($request->token);

        if ($validate !== true) {
            return $validate;
        }

        try {
            $locationCount = $this->reachTruckAction->getLocationCount();

            foreach ($locationCount as $key => $location) {
                if ($location->type == Location::LINES) {
                    $location->type = "WH TO ASSEMBLY LINE";
                }

                if ($location->type == Location::LOADING) {
                    $location->type = "WH TO LOADING";
                }
            }

            return $this->showAll($locationCount, 200);
        } catch (Exception $ex) {
            return $this->errorResponse($ex->getMessage(), 500);
        }
    }

    public function getCreateData(Request $request)
    {
        $validate = $this->validation_token($request->token);

        if ($validate !== true) {
            return $validate;
        }

        if (empty($request->location_type)) {
            throw new Exception("Location Type is required");
        }

        try {
            $createData = $this->reachTruckAction->getCreateData($request->location_type);
            unset($createData['reachTruckUsers']);

            return $this->showAll($createData, 200);
        } catch (Exception $ex) {
            return $this->errorResponse($ex->getMessage(), 500);
        }

        return $this->showOne("Pallet Transfer Successfully");
    }

    public function store(Request $request)
    {
        $validate = $this->validation_token($request->token);

        if ($validate !== true) {
            return $validate;
        }

        $validator = Validator::make($request->all(), ReachTruck::CREATE_RULES);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 500);
        }

        try {
            $data = $this->prepareData($request);
            $this->reachTruckAction->processTransferPallet($data);
        } catch (Exception $ex) {
            return $this->errorResponse($ex->getMessage(), 500);
        }
    }

    public function prepareData(Request $request)
    {
        return [
            'transfered_by' => $request->transfered_by,
            'reach_truck_id' => $request->reach_truck_id,
            'from_locationable_type' => $request->from_locationable_type,
            'from_locationable_id' => $request->from_locationable_id,
            'to_locationable_type' => $request->to_locationable_type,
            'to_locationable_id' => $request->to_locationable_id,
            'created_by' => $request->created_by,
            'updated_by' => $request->updated_by,
        ];
    }

    public function getPalletForReachTruck(Request $request)
    {
        $validate = $this->validation_token($request->token);

        if ($validate !== true) {
            return $validate;
        }

        try {
            $data = $this->reachTruckAction->getPalletForReachTruck($request->all());
        } catch (Exception $ex) {
            return $this->errorResponse($ex->getMessage(), 500);
        }

        return $this->showAll($data, 200);
    }
}
