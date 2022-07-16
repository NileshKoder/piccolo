<?php

namespace App\Features\Masters\Variants\Http\v1\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVariantRequest extends FormRequest
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
            'name' => 'required|string|unique:variants,name',
        ];
    }

    public function toFormData()
    {
        return [
            'name' => $this->name,
        ];
    }
}
