<?php

namespace Tests\Unit\Api;

use App\Models\User;
use Tests\TestCase;
// use PHPUnit\Framework\TestCase;
use Illuminate\Http\Response;

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

        $response = $this->postJson('/api/register', $userData);
        $response->assertStatus(Response::HTTP_CREATED);
        $this->assertDatabaseHas('users',[
                'id',
                'name',
                'email',
                'created_at',
                'updated_at'
            ],
            'token'
        );
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

        $response = $this->postJson('/api/login', $loginData);

        $response->assertStatus(Response::HTTP_CREATED);
        $this->assertDatabaseHas('users',[
                'id',
                'name',
                'email',
                'created_at',
                'updated_at'
            ],
            'token'
        );
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
                'status' => true,
                'message' => 'User successfully logged out'
            ]);
    }
}
