<?php

namespace App\Features\Process\PalletManagement\Domains\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use App\Features\Masters\Boxes\Domains\Models\Box;
use App\Features\Process\PalletManagement\Domains\Query\PalletBoxDetailsScopes;

class PalletBoxDetails extends Model implements Auditable
{
    use PalletBoxDetailsScopes;
    use \OwenIt\Auditing\Auditable;

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
