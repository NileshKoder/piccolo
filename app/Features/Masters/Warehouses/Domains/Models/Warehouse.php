<?php

namespace App\Features\Masters\Warehouses\Domains\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use App\Features\Masters\Warehouses\Constants\WarehouseConstants;
use App\Features\Masters\Warehouses\Domains\Query\WarehouseScopes;
use App\Features\Process\ReachTruck\Domains\Models\ReachTruck;

class Warehouse extends Model implements WarehouseConstants
{
    use WarehouseScopes;

    protected $fillable = ['name', 'type', 'is_empty'];
    protected $hidden = ['created_at', 'upated_at'];

    public function checkIsEmpty()
    {
        return ($this->is_empty) ? true : false;
    }

    public static function persistCreateWarehouse(array $warehouseData): ?Warehouse
    {
        $warehouse = null;
        $warehouse = DB::transaction(function () use ($warehouseData) {
            return Warehouse::create($warehouseData);
        });

        return $warehouse;
    }

    public static function persistUpdateWarehouse(Warehouse $warehouse, array $warehouseData): Warehouse
    {
        DB::transaction(function () use ($warehouse, $warehouseData) {
            $warehouse->update($warehouseData);
        });

        return $warehouse;
    }

    public static function persistDeleteWarehouse(Warehouse $warehouse)
    {
        return DB::transaction(function () use ($warehouse) {
            return $warehouse->delete();
        });
    }

    public static function updateAsEmpty(Warehouse $warehouse): Warehouse
    {
        $warehouse->is_empty = 1;
        $warehouse->update();

        return $warehouse;
    }

    public static function updateAsNonEmpty(Warehouse $warehouse): Warehouse
    {
        $warehouse->is_empty = 0;
        $warehouse->update();

        return $warehouse;
    }

    public function updateIsEmpty(bool $isEmpty): Warehouse
    {
        $this->is_empty = $isEmpty;
        $this->update();

        return $this;
    }

    public function toReachTrucks()
    {
        return $this->morphMany(ReachTruck::class, 'toLocationable');
    }

    public function fromReachTrucks()
    {
        return $this->morphMany(ReachTruck::class, 'fromLocationable');
    }
}
