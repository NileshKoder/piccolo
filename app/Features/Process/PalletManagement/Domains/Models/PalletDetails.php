<?php

namespace App\Features\Process\PalletManagement\Domains\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Features\Masters\SkuCodes\Domains\Models\SkuCode;
use App\Features\Masters\Variants\Domains\Models\Variant;
use App\Features\OrderManagement\Domains\Models\OrderItemPallet;
use App\Features\Process\PalletManagement\Domains\Models\Pallet;
use App\Features\Process\PalletManagement\Domains\Query\PalletDetailsScopes;

class PalletDetails extends Model
{
    use PalletDetailsScopes;
    protected $fillable = ['pallet_id', 'sku_code_id', 'variant_id', 'weight', 'batch', 'batch_date'];

    public static function persistUpdateOrCreatePalletDetails(Pallet $pallet, array $palletDetails)
    {
        $palletDetails['batch_date'] = Carbon::parse($palletDetails['batch_date'])->format('Y-m-d');
        return $pallet->palletDetails()->updateOrcreate(
            [
                'id' => $palletDetails['id']
            ],
            $palletDetails
        );
    }

    public static function persistdeletePalletDetailsWhereNotInIds(Pallet $pallet, array $ids)
    {
        return $pallet->palletDetails()->whereNotIn('id', $ids)->delete();
    }

    public function pallet()
    {
        return $this->belongsTo(Pallet::class);
    }

    public function skuCode()
    {
        return $this->belongsTo(SkuCode::class);
    }

    public function variant()
    {
        return $this->belongsTo(Variant::class);
    }

    public function orderItemPallet()
    {
        return $this->hasOne(OrderItemPallet::class, 'pallet_detail_id');
    }
}
