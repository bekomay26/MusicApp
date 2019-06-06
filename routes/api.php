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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    // dd('ff');
    return $request->user();
});

// Route::post('register', 'RegisterController@create')->name('register');
// Route::post('ss', 'FavoritesController@store');

Route::prefix('v1')->group(function () {
    Route::post('kk', 'SongsController@index');
    Route::post('register', 'UsersController@register');
    Route::post('login', 'UsersController@login');
    Route::post('logout', 'UsersController@logout');
    Route::get('logout', 'UsersController@logout');Route::post('ss', 'FavoritesController@store');
    
});

Route::middleware('auth:api')->prefix('v1')->group(function () {
    Route::post('albums', 'AlbumsController@store');
    Route::post('albums/{id}/tracks', 'AlbumsController@addSongs');
    Route::post('songs', 'SongsController@store');
    Route::get('songs/{id}', 'SongsController@show');
    Route::get('songs/download/{id}', 'SongsController@download');
    Route::post('genres', 'GenresController@store');
    Route::get('genres/{id}', 'GenresController@show');
    Route::post('ratings', 'RatingsController@store');
    Route::post('subscribes', 'SubscriptionsController@subscribe');
    Route::get('subscribes/invoice', 'SubscriptionsController@downloadLatestInvoice');
});