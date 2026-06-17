<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withHeaders([
            'Accept' => 'application/json',
        ]);
    }

    public function test_register_a_user()
    {
        $response = $this->post('/api/auth/register', [
            'email' => 'eng.mr.gohari@gmail.com',
            'name' => 'mohammadreza gohari',
            'password' => '12345678',
        ]);

        $response
            ->assertOk()
            ->assertJsonStructure(['token', 'name', 'message']);

        $this->assertDatabaseHas('users', [
            'email' => 'eng.mr.gohari@gmail.com',
        ]);
    }

    public function test_register_validation_errors_return_bad_request()
    {
        $response = $this->post('/api/auth/register', [
            'email' => 'not-an-email',
        ]);

        $response
            ->assertStatus(400)
            ->assertJsonStructure(['validationError']);
    }

    public function test_login_a_user()
    {
        User::factory()->create(['email' => 'eng.mr.gohari1@gmail.com']);

        $response = $this->post('/api/auth/login', [
            'email' => 'eng.mr.gohari1@gmail.com',
            'password' => 'password',
        ]);

        $response
            ->assertOk()
            ->assertJsonStructure(['token', 'name', 'message']);
    }

    public function test_login_rejects_wrong_password()
    {
        User::factory()->create(['email' => 'eng.mr.gohari1@gmail.com']);

        $response = $this->post('/api/auth/login', [
            'email' => 'eng.mr.gohari1@gmail.com',
            'password' => 'wrong-password',
        ]);

        $response
            ->assertStatus(400)
            ->assertJsonStructure(['validationError']);
    }

    public function test_logout_deletes_current_token()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->post('/api/auth/logout');

        $response
            ->assertOk()
            ->assertJson(['message' => 'successfully logged out']);
    }
}
