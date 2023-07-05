<?php

namespace App\Features\Process\ReachTruck\Http\Requests;

use App\Features\Process\ReachTruck\Domains\Models\ReachTruck;
use Illuminate\Foundation\Http\FormRequest;

class StoreReachTruchRequest extends FormRequest
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
        return ReachTruck::CREATE_RULES;
    }

    public function toFormData()
    {
        return [
            'transfered_by' => $this->transfered_by,
            'reach_truck_id' => $this->reach_truck_id,
            'from_locationable_type' => $this->from_locationable_type,
            'from_locationable_id' => $this->from_locationable_id,
            'to_locationable_type' => $this->to_locationable_type,
            'to_locationable_id' => $this->to_locationable_id,
            'created_by' => auth()->user()->id,
            'updated_by' => auth()->user()->id,
        ];
    }
}
