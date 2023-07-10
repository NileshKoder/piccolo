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
Route::resource('orders', OrderController::class);
