<?php

namespace Tests\Feature;

use App\Models\Basket;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BasketTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_add_product_to_basket()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create([
            'user_id' => $user->id,
            'visibility' => 1,
        ]);

        $response = $this->withHeader('Cookie', 'identity=customer-cookie')
            ->postJson('/api/baskets/user/store', [
                'product' => $product->id,
                'count' => 2,
            ]);

        $response
            ->assertOk()
            ->assertJsonPath('data.count', 2);

        $this->assertDatabaseHas('basket_product', [
            'product_id' => $product->id,
            'count' => 2,
        ]);
    }

    public function test_customer_can_list_basket_items_by_cookie()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create([
            'user_id' => $user->id,
            'visibility' => 1,
        ]);
        $basket = Basket::create(['cookie_identity' => 'customer-cookie']);
        $basket->Products()->attach($product->id, ['count' => 3]);

        $response = $this->withHeader('Cookie', 'identity=customer-cookie')
            ->getJson('/api/baskets');

        $response
            ->assertOk()
            ->assertJsonFragment(['basket' => $basket->id])
            ->assertJsonFragment(['count' => 3]);
    }

    public function test_customer_can_update_basket_item_count()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create([
            'user_id' => $user->id,
            'visibility' => 1,
        ]);
        $basket = Basket::create(['cookie_identity' => 'customer-cookie']);
        $basket->Products()->attach($product->id, ['count' => 1]);

        $response = $this->patchJson('/api/baskets/update/' . $basket->id, [
            'product' => $product->id,
            'count' => 5,
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('data.count', 5);

        $this->assertDatabaseHas('basket_product', [
            'basket_id' => $basket->id,
            'product_id' => $product->id,
            'count' => 5,
        ]);
    }

    public function test_customer_can_delete_basket_item()
    {
        $basket = Basket::create(['cookie_identity' => 'customer-cookie']);

        $response = $this->deleteJson('/api/baskets/delete/' . $basket->id);

        $response
            ->assertOk()
            ->assertJson(['message' => 'success']);

        $this->assertSoftDeleted('baskets', ['id' => $basket->id]);
    }
}
