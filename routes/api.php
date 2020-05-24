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

    // Authentication routes

    Route::get('/getUser', 'API\AuthController@getUser');

    // Pokemon routes
    
    Route::get('/getPokemon', 'API\PokemonController@getPokemon');

    Route::get('/getUserPokemons', 'API\PokemonController@getUserPokemons');

    Route::post('/saveUserPokemon', 'API\PokemonController@saveUserPokemom');

    // Pokemon Startup routes

    Route::get('/getStartupPokemons', 'API\StartUpPokemonController@getPokemons');

});