<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\PokemonUser;
use App\PokemonUserStat;
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
            $api = new PokeApi;

            $apiPokemon = json_decode($api->pokemon($request->pokemon_id));

            $pokemonUser = new PokemonUser;
            $pokemonUser->user_id = $request->user_id;
            $pokemonUser->pokemon_id = $request->pokemon_id;
            $pokemonUser->save();

            foreach ($apiPokemon->stats as $statApi) {
                $randomIv = rand(0,31);
                $pokemonStat = new PokemonUserStat;
                $pokemonStat->pokemon_user_id = $pokemonUser->id; 
                $pokemonStat->name = $statApi->stat->name; 
                $pokemonStat->base_stat = $statApi->base_stat; 
                $pokemonStat->effort = $statApi->effort; 
                $pokemonStat->iv_value = $randomIv; 
                $pokemonStat->save();
            }

            $highestStat = $pokemonUser->stats()->orderBy('iv_value', 'desc')->first();

            $apiPokemonStat = json_decode($api->pokemonStat($highestStat->name));

            foreach ($apiPokemonStat->characteristics as $ch) {
                $valueId = basename($ch->url);
                $apiCh = json_decode($api->characteristic($valueId));
                foreach ($apiCh->possible_values as $key => $value) {
                    if ($highestStat->iv_value == $value) {
                        $highestStat->characteristic = $apiCh->descriptions[1]->description;
                        $highestStat->save();
                    }
                }
            }

            $pokemonUserStats = $pokemonUser->stats;

            foreach ($pokemonUserStats as $pUS) {
                if ($pUS->name == 'hp') {
                    $formula = floor((2 * $pUS->base_stat + $pUS->iv_value + $pUS->effort) * $pUS->pokemon->level / 100 + $pUS->pokemon->level + 10);
                    $pUS->stat = $formula;
                    $pUS->save();
                } else {
                    $formula = floor(floor((2 * $pUS->base_stat + $pUS->iv_value + $pUS->effort) * $pUS->pokemon->level / 100 + 5) * 1);
                    $pUS->stat = $formula;
                    $pUS->save();
                }
            }

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
            $pokemons = $user->pokemons;

            $api = new PokeApi;


            foreach($pokemons as $pU){
                $apiPokemon = json_decode($api->pokemon($pU->pokemon_id));
                $pU->setAttribute('pokemonApi', $apiPokemon);
            }

            return response()->json($pokemons, 200);
          
        } catch (\Throwable $th) {

            return response()->json(['error' => $th->getMessage(), 'line' => $th->getLine()], 500);

        }
    }


}