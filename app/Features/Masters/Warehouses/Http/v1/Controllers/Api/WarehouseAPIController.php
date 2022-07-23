<?php

namespace App\Features\Masters\Warehouses\Http\v1\Controllers\Api;

use Exception;
use Illuminate\Http\Request;
use App\Features\Common\ApiTrait;
use App\Http\Controllers\Controller;
use App\Features\Masters\Warehouses\Actions\WarehouseAction;

class WarehouseAPIController extends Controller
{
    use ApiTrait;

    private $warehouseAction;

    public function __construct(WarehouseAction $warehouseAction)
    {
        $this->warehouseAction = $warehouseAction;
    }
}
