<?php

namespace App\Features\Process\PalletManagement\Domains\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use App\Features\Masters\SkuCodes\Domains\Models\SkuCode;
use App\Features\Masters\Variants\Domains\Models\Variant;
use App\Features\OrderManagement\Domains\Models\OrderItemPallet;
use App\Features\Process\PalletManagement\Domains\Models\Pallet;
use App\Features\Process\PalletManagement\Observers\PalletDetailsObserver;
use App\Features\Process\PalletManagement\Domains\Query\PalletDetailsScopes;

use function strtotime;

class PalletDetails extends Model implements Auditable
{
    use PalletDetailsScopes;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['pallet_id', 'sku_code_id', 'variant_id', 'weight', 'batch', 'batch_date'];

    public static function persistUpdateOrCreatePalletDetails(Pallet $pallet, array $palletDetails)
    {
        $palletDetails['batch_date'] = Carbon::parse($palletDetails['batch_date'])->format('Y-m-d');
        if(!empty($palletDetails['batch_prefix'])) {
            $palletDetails['batch'] = $palletDetails['batch_prefix']. date('dmY', strtotime($palletDetails['batch_date']));
        }

        return $pallet->palletDetails()->updateOrcreate(
            [
                'id' => $palletDetails['id']
            ],
            $palletDetails
        );
    }

    public static function persistdeletePalletDetailsWhereNotInIds(Pallet $pallet, array $ids)
    {
        $pallet->palletDetails()->whereNotIn('id', $ids)->get()->each(function ($palletDetail) {
            if($palletDetail->orderItemPallet) {
                $palletDetail->orderItemPallet->delete();
            }
            $palletDetail->delete();
        });

        return;
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
