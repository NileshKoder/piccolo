<?php

use App\Features\OrderManagement\Http\Controllers\Api\OrderApiController;
use Illuminate\Support\Facades\Route;

Route::post('get-orders', [OrderApiController::class, 'getTransfferedOrders']);
Route::post('update-order-state-as-complete/{order}', [OrderApiController::class, 'updateStateAsComplete']);
