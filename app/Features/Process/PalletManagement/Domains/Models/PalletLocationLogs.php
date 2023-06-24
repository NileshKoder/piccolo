<?php

namespace App\Features\Process\PalletManagement\Domains\Models;

use App\Helpers\Traits\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use App\Features\Process\PalletManagement\Domains\Query\PalletLocationLogsScopes;

class PalletLocationLogs extends Model
{
    use BelongsTo;
    use PalletLocationLogsScopes;

    protected $fillable = ['pallet_id', 'locationable_type', 'locationable_id', 'created_by'];

    public static function persistPalletLocation(Pallet $pallet, array $palletLocationDetails)
    {
        return $pallet->palletLocationLogs()->create($palletLocationDetails);
    }

    public function pallet()
    {
        return $this->belongsTo(Pallet::class);
    }

    public function locationable()
    {
        return $this->morphTo();
    }
}
