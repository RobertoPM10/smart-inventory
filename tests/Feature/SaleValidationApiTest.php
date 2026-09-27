<?php

namespace Tests\Feature;

use App\Models\ProductModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SaleValidationApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_json_error_when_stock_is_insufficient(): void
    {
        $product = ProductModel::create([
            'name' => 'Mouse Gaming',
            'sku' => 'MS-G',
            'price' => 300.00,
            'stock' => 1,
        ]);

        $response = $this->postJson('/api/sales', [
            'items' => [
                ['product_id' => $product->id, 'quantity' => 5]
            ]
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'error' => 'Stock insuficiente para el producto Mouse Gaming.'
            ]);
    }
}