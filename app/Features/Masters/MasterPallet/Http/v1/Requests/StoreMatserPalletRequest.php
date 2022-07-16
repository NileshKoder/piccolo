<?php

namespace App\Features\Masters\MasterPallet\Http\v1\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMatserPalletRequest extends FormRequest
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
            'name' => 'required|string||unique:master_pallets,name',
        ];
    }

    public function toFormData()
    {
        return [
            'name' => $this->name,
        ];
    }
}
