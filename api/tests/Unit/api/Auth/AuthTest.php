<?php

namespace Tests\Unit\Api;

use App\Models\User;
use Tests\TestCase;
// use PHPUnit\Framework\TestCase;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_can_register_user()
    {
        $userData = [
            'name' => 'fatima',
            'email' => 'fatima@gmail.com',
            'password' => '123456'
        ];
    
        $response = $this->postJson('/api/register', $userData);
        $response->assertStatus(200);
        $this->assertDatabaseHas('users', [
            
                'name' => 'fatima',
                'email' => 'fatima@gmail.com'
            
        ]);
    }

    public function test_can_login_user()
    {
        $user = User::factory()->create([
            'email' => 'fatimaggg@gmail.com',
            'password' => bcrypt('123456')
        ]);
    
        $loginData = [
            'email' => 'fatima@gmail.com',
            'password' => '123456'
        ];
    
        $response = $this->postJson('/api/login', $loginData);
    
        $response->assertStatus(Response::HTTP_OK)->assertJsonStructure([
            'status',
            'user',
            'token',
        ]);
    }

    public function test_can_logout_user()
    {
        $user = User::factory()->create();
        $token = $user->createToken('test_token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/logout');

        $response->assertStatus(200)
            ->assertJson([
                'statut' => true,
                'message' => 'user successflly logged out'
            ]);
    }
}
