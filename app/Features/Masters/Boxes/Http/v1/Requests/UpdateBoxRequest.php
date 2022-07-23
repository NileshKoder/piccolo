<?php

namespace App\Features\Masters\Boxes\Http\v1\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Features\Masters\CrateCodes\Domains\Models\CrateCode;

class UpdateBoxRequest extends FormRequest
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
            'name' => 'required|string|unique:boxes,name,' . $this->id,
        ];
    }

    public function toFormData()
    {
        return [
            'name' => $this->name,
        ];
    }
}
