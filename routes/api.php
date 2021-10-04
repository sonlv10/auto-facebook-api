<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
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

Route::post('login', 'AuthController@login');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('user','AuthController@getUserInfo');
    Route::post('store-token', 'AuthController@storeFbToken');
    Route::post('logout', 'AuthController@logout');
    Route::group(['prefix' => 'facebook'], function () {
        Route::post('login', 'FacebookUsersController@login');
        Route::get('get-content', 'FacebookUsersController@getPageContent');
        Route::post('user-info', 'FacebookUsersController@getUserInfo');
        Route::post('user-friends', 'FacebookUsersController@getUserFriends');
        Route::get('users', 'FacebookUsersController@getListUsers');
        Route::group(['prefix' => 'post'], function () {
            Route::post('me', 'FacebookUsersController@post');
            Route::post('get-comments', 'PostsController@getComments');
            Route::post('get-all-comments', 'PostsController@getAllComments');
        });
    });
});


