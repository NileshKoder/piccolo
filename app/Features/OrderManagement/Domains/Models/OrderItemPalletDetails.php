<?php

namespace App\Features\OrderManagement\Domains\Models;

use Illuminate\Database\Eloquent\Model;
use App\Features\OrderManagement\Domains\Models\OrderItemPallet;
use Carbon\Carbon;

class OrderItemPalletDetails extends Model
{
    protected $fillable = ['order_item_id', 'pallet_name', 'weight_in_pallet', 'mapped_weight', 'transfered_by', 'transfered_at'];

    public static function procressOrderItemPalletDetails(OrderItemPallet $orderItemPallet, $mappingWeight)
    {
        return OrderItemPalletDetails::create([
            'order_item_id' => $orderItemPallet->order_item_id,
            'pallet_name' => $orderItemPallet->pallet->masterPallet->name,
            'weight_in_pallet' => $orderItemPallet->palletDetail->weight,
            'mapped_weight' => $mappingWeight,
        ]);
    }

    public function updateTransferDetails(string $userName)
    {
        $this->transfered_by = $userName;
        $this->transfered_at = Carbon::now();

        $this->update();
    }

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }
}