<?php

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

Auth::routes();

Route::middleware('auth')->group(function () {
    Route::get('/', 'GameController@index')->name('games.index');
    Route::get('/games/{game}', 'GameController@show')->name('games.show');
});

Route::get('mongo', function (Request $request) {
    $collection = mongo()->tasks;

    $collection->insertOne([
        'pavle' => 'pls',
        5 => 'nevim uš'
    ]);

    return $collection->find(['pavle' => 'pls'])->toArray();
});
