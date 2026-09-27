<?php

namespace Tests\Feature;

use App\Models\ProductModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SaleApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_and_persists_a_sale_successfully_via_api(): void
    {
        $product = ProductModel::create([
            'name' => 'Teclado Mecanico',
            'sku' => 'TEC-001',
            'price' => 500.00,
            'stock' => 10,
        ]);

        $response = $this->postJson('/api/sales', [
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 2,
                ]
            ]
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Venta registrada con éxito.',
                'data' => [
                    'gross_total' => 1000.00,
                    'discount_total' => 0.0,
                    'net_total' => 1000.00,
                    'items_count' => 1,
                ]
            ]);

        // Validar registro en tabla sales
        $this->assertDatabaseHas('sales', [
            'gross_total' => 1000.00,
            'net_total' => 1000.00,
        ]);

        // Validar registro en tabla sale_items
        $this->assertDatabaseHas('sale_items', [
            'product_id' => $product->id,
            'quantity' => 2,
            'unit_price' => 500.00,
            'subtotal' => 1000.00,
        ]);

        // Validar actualización de stock de producto
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock' => 8,
        ]);
    }
}