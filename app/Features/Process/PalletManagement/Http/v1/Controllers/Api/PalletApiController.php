<?php

namespace App\Features\Process\PalletManagement\Http\v1\Controllers\Api;

use App\Features\Masters\Locations\Domains\Models\Location;
use App\Features\Masters\MasterPallet\Domains\Models\MasterPallet;
use App\Features\Process\PalletManagement\Actions\PalletAction;
use App\Features\Process\PalletManagement\Domains\Models\Pallet;
use App\Http\Controllers\ApiController;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PalletApiController extends ApiController
{
    private $palletAction;

    public function __construct(PalletAction $palletAction)
    {
        $this->palletAction = $palletAction;
    }

    public function create(Request $request)
    {
        $validate = $this->validation_token($request->token);

        if ($validate !== true) {
            return $validate;
        }

        try {
            $masterData = $this->palletAction->getMasterData();
        } catch (Exception $ex) {
            return $this->errorResponse($ex->getMessage(), 500);
        }

        return $this->showAll($masterData, 200);
    }

    public function getPalletDetails(Request $request)
    {
        $validate = $this->validation_token($request->token);

        if ($validate !== true) {
            return $validate;
        }

        try {
            if (empty($request->pallet_name)) {
                throw new Exception("Pallet no is missing");
            }

            $masterPallet = MasterPallet::where('name', $request->pallet_name)->first();

            if (empty($masterPallet)) {
                throw new Exception('Pallet is invalid');
            }

            $pallet = Pallet::with(
                'masterPallet',
                'palletDetails.skuCode',
                'palletDetails.variant'
            )->masterPalletName($request->pallet_name)->first();

            return $this->showOne($pallet, 200);
        } catch (Exception $ex) {
            return $this->errorResponse($ex->getMessage(), 500);
        }
    }

    public function store(Request $request)
    {
        $validate = $this->validation_token($request->token);
        if ($validate !== true) {
            return $validate;
        }

        $validator = Validator::make($request->all(), Pallet::getCreateValidationRules());

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 500);
        }

        try {
            $masterPallet = MasterPallet::find($request->master_pallet_id);

            if (!$masterPallet->is_empty) {
                throw new Exception('This pallet is filled already');
            }

            $data = $this->prepareData($request);
            $pallet = $this->palletAction->createPallet($data);
        } catch (Exception $ex) {
            return $this->errorResponse($ex->getMessage(), 500);
        }

        return $this->showOne($pallet, 200);
    }

    public function update(Pallet $pallet, Request $request)
    {
        $validate = $this->validation_token($request->token);
        if ($validate !== true) {
            return $validate;
        }

        $validator = Validator::make($request->all(), Pallet::getCreateValidationRules());

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 500);
        }

        try {
            $data = $this->prepareData($request);
            $this->palletAction->updatePallet($pallet, $data);
        } catch (Exception $ex) {
            return $this->errorResponse($ex->getMessage(), 500);
        }
    }

    public function prepareData(Request $request): array
    {
        $requestData = [];
        $requestData['pallet']['master_pallet_id'] = $request->master_pallet_id;
        $requestData['pallet']['created_by'] = $request->created_by;
        $requestData['pallet']['updated_by'] = $request->updated_by ?? $request->created_by;

        $requestData['pallet_details'] = $request->pallet_details;

        $requestData['pallet_location']['locationable_type'] = Location::class;
        $requestData['pallet_location']['locationable_id'] = $request->location_id;
        $requestData['pallet_location']['created_by'] = $request->created_by;

        return $requestData;
    }
}
