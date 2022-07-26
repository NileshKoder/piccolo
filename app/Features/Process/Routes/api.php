<?php

use Illuminate\Support\Facades\Route;

Route::post('pallets/create', 'App\Features\Process\PalletManagement\Http\v1\Controllers\Api\PalletApiController@create');
Route::post('pallets/get-pallet-details', 'App\Features\Process\PalletManagement\Http\v1\Controllers\Api\PalletApiController@getPalletDetails');
Route::post('pallets/store', 'App\Features\Process\PalletManagement\Http\v1\Controllers\Api\PalletApiController@store');
Route::put('pallets/update/{pallet}', 'App\Features\Process\PalletManagement\Http\v1\Controllers\Api\PalletApiController@update');
