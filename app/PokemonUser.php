<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PokemonUser extends Model
{
    public function stats()
    {
        return $this->hasMany('App\PokemonUserStat', 'pokemon_user_id', 'id');
    }

}
