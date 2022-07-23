<?php

namespace App\Features\Masters\Variants\Domains\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use App\Features\Masters\Variants\Domains\Query\VariantScopes;

class Variant extends Model
{
    use VariantScopes;

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
}
