<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PokemonUserStat extends Model
{
    public function pokemon()
    {
        return $this->hasOne('App\PokemonUser', 'id', 'pokemon_user_id');
    }
}
