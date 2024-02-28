<?php

namespace Tests\Unit;

use App\Models\User;
use PHPUnit\Framework\TestCase;

class AuthTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_can_register_user()
    {
        $userData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password'
        ];

        $response = $this->postJson('/api/auth/register', $userData);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'user' => [
                    'id',
                    'name',
                    'email',
                    'created_at',
                    'updated_at'
                ],
                'token'
            ]);
    }

    public function test_can_login_user()
    {
        $user = User::factory()->create([
            'email' => 'john@example.com',
            'password' => bcrypt('password')
        ]);

        $loginData = [
            'email' => 'john@example.com',
            'password' => 'password'
        ];

        $response = $this->postJson('/api/auth/login', $loginData);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'user' => [
                    'id',
                    'name',
                    'email',
                    'created_at',
                    'updated_at'
                ],
                'token'
            ]);
    }

    public function test_can_logout_user()
    {
        $user = User::factory()->create();
        $token = $user->createToken('test_token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/auth/logout');

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => 'User successfully logged out'
            ]);
    }
}
