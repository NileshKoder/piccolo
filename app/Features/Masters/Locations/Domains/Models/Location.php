<?php

namespace App\Features\Masters\Locations\Domains\Models;

use Illuminate\Database\Eloquent\Model;
use App\Features\Masters\Locations\Constants\LocationConstants;
use App\Features\Masters\Locations\Domains\Query\LocationScopes;
use App\Features\Process\PalletCreations\Domains\Models\PalletCreation;

class Location extends Model implements LocationConstants
{
    use LocationScopes;

    protected $table = "locations";

    protected $fillable = ['name', 'abbr', 'type'];

    protected $hidden = ['created_at', 'updated_at'];

    public function currentLocation()
    {
        return $this->hasMany(PalletCreation::class, 'current_location_id');
    }
}
