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

Route::post('pallets/get-all-pallets/ajax', '\App\Features\Process\PalletManagement\Http\v1\Controllers\PalletController@getAllPallets')->name('pallets.getAllPallets');
Route::resource('pallets', '\App\Features\Process\PalletManagement\Http\v1\Controllers\PalletController');
