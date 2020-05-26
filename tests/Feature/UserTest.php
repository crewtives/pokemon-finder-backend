<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testCreateUser()
    {
        $response = $this->postJson('/api/authenticate', ['name' => 'testuser', 'email'=> 'test@email.com']);

        $response->assertStatus(200)->assertJson([
                'token' => true,
                'user' => true
            ]);
    }

    public function testGetUser()
    {
        $authenticate = $this->postJson('/api/authenticate', ['name' => 'testuser', 'email'=> 'test@email.com'])
        ->assertOk()
        ->decodeResponseJson();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$authenticate['token'].'',
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->json('GET', '/api/getUser');

        $response->assertStatus(200);
    }
}
