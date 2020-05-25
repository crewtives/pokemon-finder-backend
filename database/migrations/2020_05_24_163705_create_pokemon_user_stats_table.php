<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePokemonUserStatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pokemon_user_stats', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('pokemon_user_id');
            $table->string('name');
            $table->integer('base_stat')->nullable();
            $table->integer('stat')->nullable();
            $table->integer('effort');
            $table->integer('iv_value');
            $table->string('characteristic')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pokemon_user_stats');
    }
}
