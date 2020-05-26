<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PokemonStartUpTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testGetStartUpPokemons()
    {
         $authenticate = $this->postJson('/api/authenticate', ['name' => 'testuser', 'email'=> 'test@email.com'])
        ->assertOk()
        ->decodeResponseJson();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$authenticate['token'].'',
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->json('GET', '/api/getStartupPokemons');

        $response->assertStatus(200);
    }
}
