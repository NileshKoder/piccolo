<?php

namespace App\Features\Masters\MasterPallet\Domains\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use App\Features\Masters\MasterPallet\Domains\Query\MasterPalletScopes;
use App\Features\Process\PalletManagement\Domains\Models\Pallet;

class MasterPallet extends Model
{
    use MasterPalletScopes;

    // set fillable
    protected $fillable = ['name', 'is_empty', 'last_locationable_type', 'last_locationable_id'];

    protected $hidden = ['created_at', 'updated_at'];

    public static function persistCreateMasterPallet(array $masterPalletData): ?MasterPallet
    {
        $masterPallet = null;
        $masterPallet = DB::transaction(function () use ($masterPalletData) {
            return MasterPallet::create($masterPalletData);
        });

        return $masterPallet;
    }

    public static function persistUpdateMasterPallet(MasterPallet $masterPallet, array $masterPalletData): MasterPallet
    {
        DB::transaction(function () use ($masterPallet, $masterPalletData) {
            $masterPallet->update($masterPalletData);
        });

        return $masterPallet;
    }

    public static function persistDeleteMasterPallet(MasterPallet $masterPallet)
    {
        return DB::transaction(function () use ($masterPallet) {
            return $masterPallet->delete();
        });
    }

    public function updateIsEmpty(bool $isEmpty): MasterPallet
    {
        $this->is_empty = $isEmpty;
        $this->update();

        return $this;
    }

    public function checkIsEmpty()
    {
        return ($this->is_empty) ? true : false;
    }

    public function pallet()
    {
        return $this->belongsTo(Pallet::class);
    }
}
