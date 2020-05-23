<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\StartupPokemon;
use PokePHP\PokeApi;
/**
 * @group Pokemons iniciales
 *
 * APIs para administrar pokemons iniciales
 */

class StartUpPokemonController extends Controller
{

    /**
	 * Tomar pokemons iniciales
	 *
     * Servicio para poder traer los pokemones iniciales
     * 
     * @authenticated
     * 
     * @response {
     *  Array,
     * }
     * 
	 */

    public function getPokemons()
    {
        try {

            $api = new PokeApi;
            $pokemonsData = StartupPokemon::all();

            $pokemons = [];

            foreach ($pokemonsData as $pokemon) {
                $apiPokemon = $api->pokemon($pokemon->pokemon_id);

                array_push($pokemons, json_decode($apiPokemon));
            }

            return response()->json($pokemons, 200);
          
        } catch (\Throwable $th) {

            return response()->json(['error' => $th->getMessage(), 'line' => $th->getLine()], 500);

        }
    }


}