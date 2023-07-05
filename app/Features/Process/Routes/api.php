<?php

use Illuminate\Support\Facades\Route;
use App\Features\Process\ReachTruck\Http\Controllers\Api\ReachTruckApiController;

Route::post('pallets/create', 'App\Features\Process\PalletManagement\Http\v1\Controllers\Api\PalletApiController@create');
Route::post('pallets/get-pallet-details', 'App\Features\Process\PalletManagement\Http\v1\Controllers\Api\PalletApiController@getPalletDetails');
Route::post('pallets/store', 'App\Features\Process\PalletManagement\Http\v1\Controllers\Api\PalletApiController@store');
Route::put('pallets/update/{pallet}', 'App\Features\Process\PalletManagement\Http\v1\Controllers\Api\PalletApiController@update');

Route::post('reach-truck/home', [ReachTruckApiController::class, 'home']);
Route::post('reach-truck/create', [ReachTruckApiController::class, 'getCreateData']);
Route::post('reach-truck/store', [ReachTruckApiController::class, 'store']);
Route::post('reach-truck/get-pallet-for-reach-truck', [ReachTruckApiController::class, 'getPalletForReachTruck']);
