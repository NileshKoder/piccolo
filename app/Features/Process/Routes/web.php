<?php

namespace App\Features\Process\Routes;

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

// Pallet Routes
Route::post('pallets/get-all-pallets/ajax', '\App\Features\Process\PalletManagement\Http\v1\Controllers\PalletController@getAllPallets')->name('pallets.getAllPallets');
Route::post('pallets/store/box-details', '\App\Features\Process\PalletManagement\Http\v1\Controllers\PalletController@storeBoxDetails')->name('pallets.store.box-details');
Route::get('pallets/create/box-details', '\App\Features\Process\PalletManagement\Http\v1\Controllers\PalletController@createWithBoxDetails')->name('pallets.create.box-details');
Route::get('pallets/edit/box-details/{pallet}', '\App\Features\Process\PalletManagement\Http\v1\Controllers\PalletController@editWithBoxDetails')->name('pallets.edit.box-details');
Route::put('pallets/update/box-details/{pallet}', '\App\Features\Process\PalletManagement\Http\v1\Controllers\PalletController@updateWithBoxDetails')->name('pallets.update.box-details');
Route::post('pallets/set-date-for-transfer-pallet-at-loading', '\App\Features\Process\PalletManagement\Http\v1\Controllers\PalletController@setDateForTransferPalletAtLoading')->name('pallet.set-date-for-transfer-at-loading');
Route::resource('pallets', '\App\Features\Process\PalletManagement\Http\v1\Controllers\PalletController');

// Reach truck routes
Route::post('reach-trucks/get-pallet-for-reach-truck/ajax', '\App\Features\Process\ReachTruck\Http\Controllers\ReachTruckController@getPalletForReachTruck');
Route::post('reach-trucks/get-reach-trucks/ajax', '\App\Features\Process\ReachTruck\Http\Controllers\ReachTruckController@getRechTrucks')->name('reach-trucks.getRechTrucks');
Route::resource('reach-trucks', '\App\Features\Process\ReachTruck\Http\Controllers\ReachTruckController');
