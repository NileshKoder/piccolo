<?php

namespace App\Features\Process\PalletManagement\Domains\Models;

use App\Features\Process\PalletManagement\Domains\Query\PalletBoxDetailsScopes;
use Illuminate\Database\Eloquent\Model;
use App\Features\Masters\Boxes\Domains\Models\Box;

class PalletBoxDetails extends Model
{
    use PalletBoxDetailsScopes;

    protected $fillable = ['pallet_id', 'box_name'];

    public static function persistUpdateOrCreatePalletBoxDetails(Pallet $pallet, array $palletBoxDetails)
    {
        return $pallet->palletBoxDetails()->updateOrcreate(
            [
                'id' => $palletBoxDetails['id']
            ],
            $palletBoxDetails
        );
    }

    public static function persistDeletePalletBoxDetailsWhereNotInIds(Pallet $pallet, array $ids)
    {
        $pallet->palletBoxDetails()->whereNotIn('id', $ids)->get()->each(function ($palletBoxDetail) {
            $palletBoxDetail->delete();
        });

        return;
    }

    public function pallet()
    {
        return $this->belongsTo(Pallet::class);
    }

    public function box()
    {
        return $this->belongsTo(Box::class);
    }
}
