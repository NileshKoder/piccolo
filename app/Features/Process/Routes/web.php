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
Route::resource('pallets', '\App\Features\Process\PalletManagement\Http\v1\Controllers\PalletController');

// Reach truck routes
Route::post('reach-trucks/get-pallet-for-reach-truck/ajax', '\App\Features\Process\ReachTruck\Http\Controllers\ReachTruckController@getPalletForReachTruck');
Route::post('reach-trucks/get-reach-trucks/ajax', '\App\Features\Process\ReachTruck\Http\Controllers\ReachTruckController@getRechTrucks')->name('reach-trucks.getRechTrucks');
Route::resource('reach-trucks', '\App\Features\Process\ReachTruck\Http\Controllers\ReachTruckController');
