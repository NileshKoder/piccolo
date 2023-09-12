<?php

namespace App\Features\Masters\SkuCodes\Domains\Models;

use App\Features\OrderManagement\Domains\Models\OrderItem;
use App\Features\Process\PalletManagement\Domains\Models\PalletDetails;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use App\Features\Masters\SkuCodes\Domains\Query\SkuCodeScopes;
use App\Features\Masters\SkuCodes\SkuCodeVariants\Domains\Models\SkuCodeVariant;

class SkuCode extends Model
{
    use SkuCodeScopes;

    // set fillable
    protected $fillable = ['name'];

    protected $hidden = ['created_at', 'updated_at'];

    public function variants()
    {
        return $this->hasMany(SkuCodeVariant::class);
    }

    public static function persistCreateSkuCode(array $skuCodeData): ?SkuCode
    {
        $skuCode = null;
        $skuCode = DB::transaction(function () use ($skuCodeData) {
            return SkuCode::create($skuCodeData);
        });

        return $skuCode;
    }

    public static function persistUpdateSkuCode(SkuCode $skuCode, array $skuCodeData): SkuCode
    {
        DB::transaction(function () use ($skuCode, $skuCodeData) {
            $skuCode->update($skuCodeData);
        });

        return $skuCode;
    }

    public function palletDetails()
    {
        return $this->hasMany(PalletDetails::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
