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
Route::post('register', 'Api\AuthController@register');
Route::post('login', 'Api\AuthController@login');

Route::group(['middleware' => 'laravel.jwt', 'namespace' => 'Api'], function() {
    Route::get('lists', 'ListsController@index')->middleware('cacheResponse:3600');
    Route::get('lists/{list_id}', 'ListsController@show')->middleware('cacheResponse:3600');
    Route::post('lists', 'ListsController@store');
    Route::patch('lists/{list_id}', 'ListsController@update');
    Route::delete('lists/{list_id}', 'ListsController@destroy');

    Route::get('members/{list_id}', 'MembersController@index')->middleware('cacheResponse:3600');
    Route::get('members/{list_id}/{subscriberHash}', 'MembersController@show')->middleware('cacheResponse:3600');
    Route::post('members/{list_id}', 'MembersController@store');
    Route::patch('members/{list_id}/{subscriberHash}', 'MembersController@update');
    Route::delete('members/{list_id}/{subscriberHash}', 'MembersController@destroy');
});



