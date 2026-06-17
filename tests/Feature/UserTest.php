<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_lists_active_users()
    {
        $activeUser = User::factory()->create(['active' => 1]);
        User::factory()->create(['active' => 0]);

        $response = $this->getJson('/api/users/active/list');

        $response
            ->assertOk()
            ->assertJsonFragment(['id' => $activeUser->id]);
    }

    public function test_it_shows_a_user()
    {
        $user = User::factory()->create(['active' => 1]);

        $response = $this->getJson('/api/users/active/' . $user->id);

        $response
            ->assertOk()
            ->assertJsonPath('data.id', $user->id);
    }
}
