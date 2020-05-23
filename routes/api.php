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

Route::post('/authenticate', 'API\AuthController@authenticate');


Route::group(['middleware' => 'auth:api'], function () {

    Route::get('/getUser', 'API\AuthController@getUser');

    Route::get('/getStartupPokemons', 'API\StartUpPokemonController@getPokemons');

});