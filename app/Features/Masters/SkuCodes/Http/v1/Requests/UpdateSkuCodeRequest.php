<?php

namespace App\Features\Masters\SkuCodes\Http\v1\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSkuCodeRequest extends FormRequest
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
            'name' => 'required|string|unique:sku_codes,name,' . $this->id,
        ];
    }

    public function toFormData()
    {
        return [
            'name' => $this->name,
        ];
    }
}
