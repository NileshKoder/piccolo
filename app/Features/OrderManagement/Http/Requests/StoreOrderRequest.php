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
        $status = '';
        if($this->save_as_draft) {
            $status = Order::DRAFT;
        } elseif ($this->save_as_ready_to_mapping) {
            $status = Order::READY_TO_MAPPING;
        }
        return [
            "order_number" => $this->order_number,
            "order_item_details" => $this->order_item_details,
            "state" => $status,
            "created_by" => auth()->user()->id,
            "updated_by" => auth()->user()->id,
        ];
    }
}
