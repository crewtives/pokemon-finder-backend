<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\PokemonUser;
use App\StartupPokemon;
use PokePHP\PokeApi;

/**
 * @group Pokemons
 *
 * APIs para administrar pokemons
 */

class PokemonController extends Controller
{

    /**
	 * Tomar pokemon
	 *
     * Servicio para poder traer informacion de un pokemon
     * 
     * @authenticated
     * 
     * @bodyParam pokemon_id string parametro para poder identificar el Pokemon
     * 
     * @response {
     *  Object,
     * }
     * 
	 */

    public function getPokemon(Request $request)
    {
        try {

            $api = new PokeApi;

            if (isset($request->pokemon_id)) {
                $apiPokemon = $api->pokemon($request->pokemon_id);
                $apiPokemonSpecies = $api->pokemonSpecies($request->pokemon_id);
                $apiPokemonStat = $api->pokemonStat($request->pokemon_id);

                return response()->json([json_decode($apiPokemon),
                'specie' => json_decode($apiPokemonSpecies),
                'stats' => json_decode($apiPokemonStat)
                ], 200);

            }
          
        } catch (\Throwable $th) {

            return response()->json(['error' => $th->getMessage(), 'line' => $th->getLine()], 500);

        }
    }

    public function saveUserPokemom(Request $request)
    {
        try {

            $pokemonUser = new PokemonUser;
            $pokemonUser->user_id = $request->user_id;
            $pokemonUser->pokemon_id = $request->pokemon_id;
            $pokemonUser->save();

            $user = \Auth::user();

            return response()->json($user->pokemons, 200);
          
        } catch (\Throwable $th) {

            return response()->json(['error' => $th->getMessage(), 'line' => $th->getLine()], 500);

        }
    }

    public function getUserPokemons()
    {
        try {
            $user = \Auth::user();

            return response()->json($user->pokemons, 200);
          
        } catch (\Throwable $th) {

            return response()->json(['error' => $th->getMessage(), 'line' => $th->getLine()], 500);

        }
    }


}