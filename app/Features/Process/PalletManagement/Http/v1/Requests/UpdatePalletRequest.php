<?php

namespace App\Features\Process\PalletManagement\Http\v1\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Features\Masters\Locations\Domains\Models\Location;

class UpdatePalletRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "location_id" => 'required|exists:locations,id',
            "master_pallet_id" => 'required|exists:master_pallets,id',
            "pallet_details.*.sku_code_id" => 'required|exists:sku_codes,id',
            "pallet_details.*.variant_id" => 'required|exists:variants,id',
            "pallet_details.*.weight" => 'required',
            "pallet_details.*.batch" => 'required',
        ];
    }

    public function requestData(): array
    {
        $requestData = [];
        $requestData['pallet']['master_pallet_id'] = $this->master_pallet_id;
        $requestData['pallet']['updated_by'] = auth()->check() ? auth()->user()->id : 1;

        $requestData['pallet_details'] = $this->pallet_details;

        $requestData['pallet_location']['locationable_type'] = Location::class;
        $requestData['pallet_location']['locationable_id'] = $this->location_id;
        $requestData['pallet_location']['created_by'] = auth()->check() ? auth()->user()->id : 1;

        $requestData['is_request_for_warehouse'] = !empty($this->request_for_warehouse) ? true : false;

        return $requestData;
    }
}
