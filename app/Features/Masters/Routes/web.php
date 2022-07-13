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
Route::post('users/change-user-state/{user}/ajax', 'App\Features\Masters\Users\Http\v1\Controllers\UserController@changeUserState');
Route::post('users/get-users/ajax', 'App\Features\Masters\Users\Http\v1\Controllers\UserController@getUsers')->name('users.getUsers');
Route::resource('users', 'App\Features\Masters\Users\Http\v1\Controllers\UserController');
