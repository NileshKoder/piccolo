<?php

namespace App\Features\Process\PalletManagement\Domains\Models;

use Illuminate\Database\Eloquent\Model;
use App\Features\Masters\Boxes\Domains\Models\Box;
use App\Features\Process\PalletManagement\Domains\Models\Pallet;

class PalletBoxDetails extends Model
{
    protected $filleable = ['pallet_id', 'order_id', 'box_id'];

    public static function persistPalletBoxDetails(Pallet $pallet, array $palletBoxDetails)
    {
        return $pallet->palletBoxDetails()->create($palletBoxDetails);
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
