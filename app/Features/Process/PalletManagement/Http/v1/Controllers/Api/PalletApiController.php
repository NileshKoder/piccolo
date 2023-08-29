<?php

namespace App\Features\Process\PalletManagement\Http\v1\Controllers\Api;

use App\Features\Masters\Locations\Domains\Models\Location;
use App\Features\Masters\MasterPallet\Domains\Models\MasterPallet;
use App\Features\OrderManagement\Domains\Models\Order;
use App\Features\OrderManagement\Domains\Models\OrderItem;
use App\Features\Process\PalletManagement\Actions\PalletAction;
use App\Features\Process\PalletManagement\Domains\Models\Pallet;
use App\Features\Process\PalletManagement\Domains\Models\PalletBoxDetails;
use App\Features\Process\PalletManagement\Domains\Models\PalletDetails;
use App\Http\Controllers\ApiController;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use function collect;

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

    public function returnCreate(Request $request)
    {
        $validate = $this->validation_token($request->token);

        if ($validate !== true) {
            return $validate;
        }

        try {
            $masterData = $this->palletAction->getMasterDataForReturn();
        } catch (Exception $ex) {
            return $this->errorResponse($ex->getMessage(), 500);
        }

        return $this->showAll($masterData, 200);
    }

    public function createBox(Request $request)
    {
        $validate = $this->validation_token($request->token);

        if ($validate !== true) {
            return $validate;
        }

        try {
            $masterData = $this->palletAction->getMastersForBoxDetails();
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

            $pallet = Pallet::with([
                    'masterPallet.lastLocation',
                    'palletDetails.orderItemPallet',
                    'palletDetails.skuCode',
                    'palletDetails.variant',
                    'order',
                    'palletBoxDetails'
                ])
                ->select('id', 'master_pallet_id')
                ->masterPalletName($request->pallet_name)
                ->first();
            if($pallet) {
                $pallet = $this->prepareDataOfPallet($pallet);
                return $this->showOne($pallet, 200);
            }

            return $this->showOne([null], 200);
        } catch (Exception $ex) {
            return $this->errorResponse($ex->getMessage(), 500);
        }
    }

    public function prepareDataOfPallet(Pallet $pallet)
    {
        $newPallet = new Pallet();
        $newPallet->id = $pallet->id;
        $newPallet->pallet_name = $pallet->masterPallet->name;

        $newPallet->pallet_last_location = $pallet->masterPallet->last_locationable->name;
        $newPallet->wh_last_location = $pallet->masterPallet->last_locationable->name;

        if($pallet->palletDetails->count() > 0) {
            $palletDetailCollection = collect();
            foreach ($pallet->palletDetails as $palletDetail) {
                $palletDetails = new PalletDetails();
                $palletDetails->id = $palletDetail->id;
                $palletDetails->sku_code_id = $palletDetail->sku_code_id;
                $palletDetails->sku_code_name = $palletDetail->skuCode->name;
                $palletDetails->variant_id = $palletDetail->variant_id;
                $palletDetails->variant_name = $palletDetail->variant->name;
                $palletDetails->weight = $palletDetail->weight;
                $palletDetails->batch = $palletDetail->batch;
//                if($palletDetail->orderItemPallet->state != OrderItem::CANCELLED) {
                    $palletDetails->mapped_weight_value = !empty($palletDetail->orderItemPallet) ? (int) $palletDetail->orderItemPallet->weight : 0;
                    $palletDetails->mapped_weight = !empty($palletDetail->orderItemPallet) ? "Mapped {$palletDetail->orderItemPallet->weight} KG weight for Order # : {$palletDetail->orderItemPallet->orderItem->order->order_number}" : "";
//                } else {
//                    $palletDetails->mapped_weight_value = 0;
//                    $palletDetails->mapped_weight =  "";
//                }


                $palletDetailCollection->push($palletDetails);
            }

            $newPallet->palletDetails = $palletDetailCollection;
        }

        if($pallet->palletBoxDetails->count() > 0) {
            if($pallet->order) {
                $newPallet->order_id = $pallet->order_id;
                $newPallet->order_name = $pallet->order->order_number;
            }
            $palletBoxDetailCollection = collect();
            foreach ($pallet->palletBoxDetails as $palletBoxDetail) {
                $palletBoxDetails = new PalletBoxDetails();
                $palletBoxDetails->id = $palletBoxDetail->id;
                $palletBoxDetails->box_name = $palletBoxDetail->box_name;

                $palletBoxDetailCollection->push($palletBoxDetails);
            }

            $newPallet->palletBoxDetails = $palletBoxDetailCollection;
        }

        return $newPallet;
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
            $this->palletAction->createPallet($data);
        } catch (Exception $ex) {
            return $this->errorResponse($ex->getMessage(), 500);
        }

        return $this->showOne(['success' => "Pallet Filled Successfully"], 200);
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

        return $this->showOne(['success' => "Pallet Updated Successfully"], 200);
    }

    public function prepareData(Request $request): array
    {
        $requestData = [];
        $requestData['pallet']['location_id'] = $request->location_id;
        $requestData['pallet']['master_pallet_id'] = $request->master_pallet_id;
        if(!empty($request->created_by)) {
            $requestData['pallet']['created_by'] = $request->created_by;
        }
        $requestData['pallet']['updated_by'] = $request->updated_by ?? $request->created_by;

        $requestData['pallet_details'] = $request->pallet_details;
        $requestData['pallet_box_details'] = $request->pallet_box_details;
        $requestData['is_request_for_warehouse'] = $request->is_request_for_warehouse;
        $requestData['is_request_for_loading'] = $request->is_request_for_loading;

        return $requestData;
    }
}
