<?php

use Illuminate\Database\Seeder;

class StartupPokemonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('startup_pokemon')->insert([
            'pokemon_id' => 1,
        ]);

        DB::table('startup_pokemon')->insert([
            'pokemon_id' => 4,
        ]);

        DB::table('startup_pokemon')->insert([
            'pokemon_id' => 7,
        ]);
    }
}
