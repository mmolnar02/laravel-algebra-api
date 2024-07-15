<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    protected string $apiPrefix;

    protected function setUp(): void
    {
        parent::setUp();
        $this->apiPrefix = env('APP_API_PREFIX', 'api/v1');
    }

    public function test_login_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt($password = 'password'),
        ]);

        $response = $this->postJson("{$this->apiPrefix}/login", [
            'email' => $user->email,
            'password' => $password,
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['access_token', 'token_type', 'expires_in', 'refresh_token']);
    }

    public function test_login_with_invalid_credentials(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt($password = 'password'),
        ]);

        $response = $this->postJson("{$this->apiPrefix}/login", [
            'email' => $user->email,
            'password' => 'invalid-password',
        ]);

        $response->assertStatus(401)
            ->assertJsonStructure(['error']);
    }

    public function test_register_with_valid_data(): void
    {
        $response = $this->postJson("{$this->apiPrefix}/register", [
            'name' => 'John Doe',
            'email' => 'test@test.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['access_token', 'token_type', 'expires_in', 'refresh_token']);

        $this->assertDatabaseHas('users', [
            'email' => 'test@test.com',
            'name' => 'John Doe',
        ]);
    }
}
