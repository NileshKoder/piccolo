<?php

namespace App\Features\Masters\Warehouses\Http\v1\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreWarehouseRequest extends FormRequest
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
            'warehouse.*.name' => 'required|unique:warehouses,name'
        ];
    }

    public function toFormData()
    {
        return [
            'warehouse' => $this->warehouse,
        ];
    }
}
