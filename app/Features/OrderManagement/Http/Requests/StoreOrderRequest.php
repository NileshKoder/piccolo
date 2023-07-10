<?php

namespace App\Features\OrderManagement\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Features\OrderManagement\Domains\Models\Order;

class StoreOrderRequest extends FormRequest
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
        return Order::CREATE_RULE;
    }

    public function toFormData()
    {
        return [
            "order_number" => $this->order_number,
            "order_item_details" => $this->order_item_details,
            "created_by" => auth()->user()->id,
            "updated_by" => auth()->user()->id,
        ];
    }
}
