<?php

use Illuminate\Support\Facades\Route;
use App\Features\Process\ReachTruck\Http\Controllers\Api\ReachTruckApiController;
use App\Features\Process\PalletManagement\Http\v1\Controllers\Api\PalletApiController;

Route::post('pallets/create', [PalletApiController::class, 'create']);
Route::post('pallets/get-pallet-details', [PalletApiController::class, 'getPalletDetails']);
Route::post('pallets/store', [PalletApiController::class, 'store']);
Route::put('pallets/update/{pallet}', [PalletApiController::class, 'update']);

Route::post('reach-truck/home', [ReachTruckApiController::class, 'home']);
Route::post('reach-truck/create', [ReachTruckApiController::class, 'getCreateData']);
Route::post('reach-truck/store', [ReachTruckApiController::class, 'store']);
Route::post('reach-truck/get-pallet-for-reach-truck', [ReachTruckApiController::class, 'getPalletForReachTruck']);
