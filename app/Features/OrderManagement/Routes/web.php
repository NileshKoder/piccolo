<?php

namespace App\Features\OrderManagement\Routes;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Route;
use App\Features\OrderManagement\Http\Controllers\OrderController;

Route::post("orders/get-orders/ajax", [OrderController::class, 'getOrders'])->name('orders.getOrders');
Route::post("orders/orderItem/unmapped-pallet", [OrderController::class, 'unmappedPallet'])->name('order.orderItem.unmappedPallet');
Route::post("orders/update-state/{order}", [OrderController::class, 'updateStateToReadyToDispatch'])->name('orders.updateStateToReadyToDispatch');
Route::get("orders/{order}/order-item/{order_item}/get-order-item-mapped-details", [OrderController::class, 'getOrderIteMappedDetails'])->name('orders.getOrderIteMappedDetails');
Route::resource('orders', OrderController::class);
