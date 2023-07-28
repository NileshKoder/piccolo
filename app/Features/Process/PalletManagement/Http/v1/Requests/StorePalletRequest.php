<?php

namespace App\Features\Process\PalletManagement\Http\v1\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Features\Masters\Locations\Domains\Models\Location;
use App\Features\Process\PalletManagement\Domains\Models\Pallet;

class StorePalletRequest extends FormRequest
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
        return Pallet::getCreateValidationRules();
    }

    public function requestData(): array
    {
        $requestData = [];
        $requestData['pallet']['master_pallet_id'] = $this->master_pallet_id;
        $requestData['pallet']['location_id'] = $this->location_id;
        $requestData['pallet']['created_by'] = auth()->check() ? auth()->user()->id : 1;
        $requestData['pallet']['updated_by'] = auth()->check() ? auth()->user()->id : 1;
        $requestData['pallet_details'] = $this->pallet_details;

        $requestData['is_request_for_warehouse'] = !empty($this->request_for_warehouse) ? true : false;

        return $requestData;
    }
}
