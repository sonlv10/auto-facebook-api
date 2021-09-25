<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'facebook'], function () {
    Route::post('login', 'FacebookUsersController@login');
    Route::get('get-content', 'FacebookUsersController@getPageContent');
    Route::post('user-info', 'FacebookUsersController@getUserInfo');
    Route::post('user-friends', 'FacebookUsersController@getUserFriends');
    Route::get('users', 'FacebookUsersController@getListUsers');
    Route::group(['prefix' => 'post'], function () {
        Route::post('me', 'FacebookUsersController@post');
    });
});

