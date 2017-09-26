<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->group(function () {
    Route::get('/user', function () {
        return auth()->user();
    });

    Route::post('/chess/{gameId}/assignSecondPlayer', 'ChessController@assignSecondPlayer');

    Route::get('/chess/{gameId}/moves', 'MovesController@index');
    Route::post('/chess/{gameId}/moves', 'MovesController@store');
});
