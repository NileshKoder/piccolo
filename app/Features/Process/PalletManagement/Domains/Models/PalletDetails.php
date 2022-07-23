<?php

namespace App\Features\Process\PalletManagement\Domains\Models;

use Illuminate\Database\Eloquent\Model;
use App\Features\Masters\SkuCodes\Domains\Models\SkuCode;
use App\Features\Masters\Variants\Domains\Models\Variant;
use App\Features\Process\PalletManagement\Domains\Models\Pallet;

class PalletDetails extends Model
{
    protected $fillable = ['pallet_id', 'sku_code_id', 'variant_id', 'weight', 'batch'];

    public static function persistUpdateOrCreatePalletDetails(Pallet $pallet, array $palletDetails)
    {
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
}
