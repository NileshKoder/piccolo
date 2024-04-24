<?php

namespace App\Features\Masters\Variants\Domains\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use App\Features\OrderManagement\Domains\Models\OrderItem;
use App\Features\Masters\Variants\Domains\Query\VariantScopes;
use App\Features\Process\PalletManagement\Domains\Models\PalletDetails;

class Variant extends Model implements Auditable
{
    use VariantScopes;
    use \OwenIt\Auditing\Auditable;

    protected $table = 'variants';

    // set fillable
    protected $fillable = ['name'];

    protected $hidden = ['created_at', 'updated_at'];

    public static function persistCreateVariant(array $variantData): ?Variant
    {
        $variant = null;
        $variant = DB::transaction(function () use ($variantData) {
            return Variant::create($variantData);
        });

        return $variant;
    }

    public static function persistUpdateVariant(array $variantData, $variant): Variant
    {
        DB::transaction(function () use ($variant, $variantData) {
            $variant->update($variantData);
        });

        return $variant;
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
