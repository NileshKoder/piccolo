<?php

namespace App\Features\Masters\Warehouses\Http\v1\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWarehouseRequest extends FormRequest
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
            'name' => 'required|unique:warehouses,name,' . $this->id
        ];
    }

    public function toFormData()
    {
        return [
            'name' => $this->name,
        ];
    }
}
