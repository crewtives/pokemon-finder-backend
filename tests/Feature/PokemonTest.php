<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PokemonTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testCreatePokemon()
    {
        $authenticate = $this->postJson('/api/authenticate', ['name' => 'testuser', 'email'=> 'test@email.com'])
        ->assertOk()
        ->decodeResponseJson();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$authenticate['token'].'',
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->json('POST', '/api/saveUserPokemon', ['user_id' => $authenticate['user']['id'], 'pokemon_id'=> 1]);

        $response->assertStatus(200);
    }

    public function testGetPokemon()
    {
        $authenticate = $this->postJson('/api/authenticate', ['name' => 'testuser', 'email'=> 'test@email.com'])
        ->assertOk()
        ->decodeResponseJson();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$authenticate['token'].'',
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->json('GET', '/api/getPokemon?pokemon_id=1', ['pokemon_id'=> 1]);

        $response->assertStatus(200);
    }

    public function testGetUserPokemon()
    {
        $authenticate = $this->postJson('/api/authenticate', ['name' => 'testuser', 'email'=> 'test@email.com'])
        ->assertOk()
        ->decodeResponseJson();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$authenticate['token'].'',
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->json('GET', '/api/getUserPokemons');

        $response->assertStatus(200);
    }
}
