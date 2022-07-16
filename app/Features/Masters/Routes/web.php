<?php

namespace App\Features\Masters\Routes;

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

// User Routes
Route::post('users/change-user-state/{user}/ajax', '\App\Features\Masters\Users\Http\v1\Controllers\UserController@changeUserState');
Route::post('users/get-users/ajax', '\App\Features\Masters\Users\Http\v1\Controllers\UserController@getUsers')->name('users.getUsers');
Route::resource('users', '\App\Features\Masters\Users\Http\v1\Controllers\UserController');

// sku code routes
Route::post('sku-codes/get-sku-codes/ajax', '\App\Features\Masters\SkuCodes\Http\v1\Controllers\SkuCodeController@getSkuCodes')->name('sku-codes.getSkuCodes');
Route::resource('sku-codes', '\App\Features\Masters\SkuCodes\Http\v1\Controllers\SkuCodeController');

// variants routes
Route::post('variants/get-variants/ajax', 'App\Features\Masters\Variants\Http\v1\Controllers\VariantController@getVariants')->name('variants.getVariants');
Route::resource('variants', 'App\Features\Masters\Variants\Http\v1\Controllers\VariantController');

// box routes
Route::post('boxes/get-boxes/ajax', 'App\Features\Masters\Boxes\Http\v1\Controllers\BoxController@getBoxes')->name('boxes.getBoxes');
Route::resource('boxes', 'App\Features\Masters\Boxes\Http\v1\Controllers\BoxController');

// master pallet routes
Route::post('master-pallets/get-pallets/ajax', 'App\Features\Masters\MasterPallet\Http\v1\Controllers\MasterPalletController@getMasterPallets')->name('master-pallets.getMasterPallets');
Route::resource('master-pallets', 'App\Features\Masters\MasterPallet\Http\v1\Controllers\MasterPalletController');

// warehouse routes
Route::post('warehouses/get-warehouses/ajax', 'App\Features\Masters\Warehouses\Http\v1\Controllers\WarehouseController@getWarehouses')->name('warehouses.getWarehouses');
Route::resource('warehouses', 'App\Features\Masters\Warehouses\Http\v1\Controllers\WarehouseController');
