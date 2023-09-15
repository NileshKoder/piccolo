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
Route::get('pallet-report/sku-details', 'App\Features\Reports\Routes\Controllers\PalletReportController@index')->name('pallet-report.index');

Route::get('pallet-reportP/get-box-pallet-report/ajax', '\App\Features\Reports\Routes\Controllers\PalletReportController@getExcelForBox')->name('box-pallet-report.getExcel');
Route::post('pallet-report/get-box-pallet-report/ajax', '\App\Features\Reports\Routes\Controllers\PalletReportController@getBoxPalletReport')->name('box-pallet-report.getBoxPalletReport');
Route::get('pallet-report/box-details', 'App\Features\Reports\Routes\Controllers\PalletReportController@boxDetailReport')->name('box-pallet-report.index');

Route::get('order-reportP/get-order-report/excel-export', '\App\Features\Reports\Routes\Controllers\OrderReportController@getExcel')->name('order-report.getExcel');
Route::post('order-report/get-order-report/ajax', '\App\Features\Reports\Routes\Controllers\OrderReportController@getOrderReport')->name('order-report.getOrderReport');
Route::get('order-report', 'App\Features\Reports\Routes\Controllers\OrderReportController@index')->name('order-report.index');

Route::get('sku-report/get-sku-report/excel-export', '\App\Features\Reports\Routes\Controllers\SkuReportController@getExcel')->name('sku-report.getExcel');
Route::post('sku-report/get-sku-report/ajax', '\App\Features\Reports\Routes\Controllers\SkuReportController@getSkuReport')->name('sku-report.getSkuReport');
Route::get('sku-report', 'App\Features\Reports\Routes\Controllers\SkuReportController@index')->name('sku-report.index');
