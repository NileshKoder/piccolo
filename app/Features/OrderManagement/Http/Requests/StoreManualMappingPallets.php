<?php

namespace App\Features\OrderManagement\Http\Requests;

use App\Features\Process\PalletManagement\Domains\Models\PalletDetails;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Collection;
use function array_key_exists;
use function auth;
use function collect;

class StoreManualMappingPallets extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'order_item_pallets.*.pallet_detail_id' => 'exists:pallet_details,id'
        ];
    }

    public function toData(): Collection
    {
        $orderItemPalletData = collect();

        foreach ($this->order_item_pallets as $orderItemPallet) {
            if(array_key_exists('pallet_detail_id', $orderItemPallet) && $orderItemPallet['is_updated'] == "true") {
                $data = [];
                $palletDetail = PalletDetails::find($orderItemPallet['pallet_detail_id']);

                $data['order_item_pallet_id'] = $orderItemPallet['order_item_pallet_id'];
                $data['pallet_id'] = $palletDetail->pallet_id;
                $data['pallet_detail_id'] = $palletDetail->id;
                $data['weight'] = $palletDetail->weight;

                $orderItemPalletData->push($data);
            }
        }

        return $orderItemPalletData;
    }
}
