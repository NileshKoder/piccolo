<?php

namespace App\Features\Masters\Locations\Domains\Models;

use App\Features\Masters\MasterPallet\Domains\Models\MasterPallet;
use App\Features\Process\PalletManagement\Domains\Models\Pallet;
use Illuminate\Database\Eloquent\Model;
use App\Features\Masters\Locations\Constants\LocationConstants;
use App\Features\Masters\Locations\Domains\Query\LocationScopes;
use App\Features\Process\PalletCreations\Domains\Models\PalletCreation;
use App\Features\Process\ReachTruck\Domains\Models\ReachTruck;

class Location extends Model implements LocationConstants
{
    use LocationScopes;

    protected $table = "locations";

    protected $fillable = ['name', 'abbr', 'type'];

    protected $hidden = ['created_at', 'updated_at'];

    public function fromLocationable()
    {
        return $this->morphMany(ReachTruck::class, 'from_locationable');
    }

    public function toLocationable()
    {
        return $this->morphMany(ReachTruck::class, 'to_locationable');
    }

    public function last_locationable()
    {
        return $this->morphOne(MasterPallet::class, 'lastLocation');
    }
}
