<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_cannot_access_seller_products()
    {
        $customer = User::factory()->create(['role' => User::ROLE_CUSTOMER]);
        Sanctum::actingAs($customer);

        $response = $this->getJson('/api/seller/products');

        $response->assertForbidden();
    }

    public function test_seller_can_list_own_products()
    {
        $seller = User::factory()->create(['role' => User::ROLE_SELLER]);
        $otherSeller = User::factory()->create(['role' => User::ROLE_SELLER]);

        $ownProduct = Product::factory()->create([
            'user_id' => $seller->id,
            'subject' => 'seller-product',
        ]);
        Product::factory()->create([
            'user_id' => $otherSeller->id,
            'subject' => 'other-product',
        ]);

        Sanctum::actingAs($seller);

        $response = $this->getJson('/api/seller/products');

        $response
            ->assertOk()
            ->assertJsonFragment(['id' => $ownProduct->id])
            ->assertJsonMissing(['subject' => 'other-product']);
    }

    public function test_admin_can_update_user_role()
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $user = User::factory()->create(['role' => User::ROLE_CUSTOMER]);

        Sanctum::actingAs($admin);

        $response = $this->patchJson('/api/admin/users/' . $user->id . '/role', [
            'role' => User::ROLE_SELLER,
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('data.role', User::ROLE_SELLER);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'role' => User::ROLE_SELLER,
        ]);
    }
}
