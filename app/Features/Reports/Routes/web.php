<?php

namespace App\Features\Reports\Routes;

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

Route::get('pallet-reportP/get-pallet-report/ajax', '\App\Features\Reports\Routes\Controllers\PalletReportController@getExcel')->name('pallet-report.getExcel');
Route::post('pallet-reportP/get-pallet-report/ajax', '\App\Features\Reports\Routes\Controllers\PalletReportController@getPalletReport')->name('pallet-report.getPalletReport');
Route::get('pallet-report', 'App\Features\Reports\Routes\Controllers\PalletReportController@index')->name('pallet-report.index');
