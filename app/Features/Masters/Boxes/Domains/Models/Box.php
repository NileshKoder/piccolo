<?php

namespace App\Features\Masters\Boxes\Domains\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use App\Features\Masters\Boxes\Domains\Query\BoxScopes;

class Box extends Model
{
    use BoxScopes;

    // set fillable
    protected $fillable = ['name', 'is_empty'];

    protected $hidden = ['created_at', 'updated_at'];

    public function checkIsEmpty()
    {
        return ($this->is_empty) ? true : false;
    }

    public static function persistCreateBox(array $boxData): ?Box
    {
        $box = null;
        $box = DB::transaction(function () use ($boxData) {
            return Box::create($boxData);
        });

        return $box;
    }

    public static function persistUpdateBox(Box $box, array $boxData): Box
    {
        DB::transaction(function () use ($box, $boxData) {
            $box->update($boxData);
        });

        return $box;
    }

    public static function persistDeleteBox(Box $box)
    {
        return DB::transaction(function () use ($box) {
            return $box->delete();
        });
    }

    public function updateAsEmpty(bool $isEmpty): Box
    {
        $this->is_empty = $isEmpty;
        $this->update();

        return $this;
    }
}
