<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_lists_only_active_products()
    {
        $user = User::factory()->create();
        $visible = Product::factory()->create([
            'user_id' => $user->id,
            'visibility' => 1,
            'subject' => 'visible-product',
        ]);
        Product::factory()->create([
            'user_id' => $user->id,
            'visibility' => 0,
            'subject' => 'hidden-product',
        ]);

        $response = $this->getJson('/api/products');

        $response
            ->assertOk()
            ->assertJsonFragment(['id' => $visible->id])
            ->assertJsonMissing(['subject' => 'hidden-product']);
    }

    public function test_it_shows_an_active_product()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create([
            'user_id' => $user->id,
            'visibility' => 1,
        ]);

        $response = $this->getJson('/api/products/show/' . $product->id);

        $response
            ->assertOk()
            ->assertJsonPath('data.id', $product->id);
    }
}
