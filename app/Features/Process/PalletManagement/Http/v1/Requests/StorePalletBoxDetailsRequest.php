<?php

namespace App\Features\Process\PalletManagement\Http\v1\Requests;

use App\Features\Process\PalletManagement\Domains\Models\Pallet;
use Illuminate\Foundation\Http\FormRequest;
use function auth;

class StorePalletBoxDetailsRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return Pallet::getCreateValidationRulesForBoxDetails();
    }

    public function requestData(): array
    {
        $requestData = [];

        $requestData['pallet']['master_pallet_id'] = $this->master_pallet_id;
        $requestData['pallet']['location_id'] = $this->location_id;
        $requestData['pallet']['created_by'] = auth()->check() ? auth()->user()->id : 1;
        $requestData['pallet']['updated_by'] = auth()->check() ? auth()->user()->id : 1;
        $requestData['pallet_box_details'] = $this->pallet_box_details;

        $requestData['is_request_for_warehouse'] = !empty($this->request_for_warehouse) ? true : false;
        $requestData['is_request_for_loading'] = !empty($this->request_for_loading) ? true : false;

        return $requestData;
    }
}
