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
     *     "0":{},
     *     "specie": {},
     *     "stats": []
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

    /**
	 * Guardar pokemon
	 *
     * Servicio para poder generar y guardar un pokemon
     * 
     * En este servicio se aplican las formulas para poder crear stats aleatorios, con la formula de pokeapi
     * de esta anera garantizamos que un pokemon no sea igual a otro 
     * Tambien tiene la regla de individual value para poder agregar descripciones especiales a cada stat
     * 
     * formulas:
     * https://www.dragonflycave.com/mechanics/stats
     * 
     * caracterisiticas
     * https://bulbapedia.bulbagarden.net/wiki/Characteristic
     * 
     * @authenticated
     * 
     * @bodyParam pokemon_id string parametro para poder identificar el Pokemon
     * @bodyParam user_id string parametro para poder asignar el pokemon al usuairo
     * 
     * @response {
     *     "user_id": 1,
     *     "pokemon_id": 1,
     *     "level": 1,
     *     "experience": 0,
     *     "stats": [],
     *     "pokemonApi": {}
     * }
     * 
	 */

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
                    $pUS->actual_stat = $formula;
                    $pUS->save();
                } else {
                    $formula = floor(floor((2 * $pUS->base_stat + $pUS->iv_value + $pUS->effort) * $pUS->pokemon->level / 100 + 5) * 1);
                    $pUS->stat = $formula;
                    $pUS->actual_stat = $formula;
                    $pUS->save();
                }
            }

            $user = \Auth::user();
            if(!$user->lab_stage){
                $user->lab_stage = true;
                $user->save();
            }

            $pokemons = $user->pokemons;

            foreach($pokemons as $pU){
                $apiPokemon = json_decode($api->pokemon($pU->pokemon_id));
                $pU->setAttribute('pokemonApi', $apiPokemon);
            }


            return response()->json($user->pokemons, 200);
          
        } catch (\Throwable $th) {

            return response()->json(['error' => $th->getMessage(), 'line' => $th->getLine()], 500);

        }
    }

    /**
	 * Tomar pokemons del usuario
	 *
     * Servicio para poder traer todos los pokemons de un usuario
     * 
     * @authenticated
     * 
     * @response {
     *     "user_id": 1,
     *     "pokemon_id": 1,
     *     "level": 1,
     *     "experience": 0,
     *     "pokemonApi": {}
     * }
	 */

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