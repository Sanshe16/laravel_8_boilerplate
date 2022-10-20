<?php

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

Route::group(['middleware' => ['cors', 'json.response']], function () {
    Route::group(['prefix' => '/v1'], function () {
        Route::group(['namespace' => 'Api\V1', 'as' => 'api.v1.'], function () {
            require base_path('routes/api_v1.php');
        });
    });
});
