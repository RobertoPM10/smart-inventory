<?php

namespace Tests\Feature;

use App\Models\ProductModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SaleApiTest extends TestCase
{
    // Reinicia la base de datos de pruebas en cada ejecucion
    use RefreshDatabase;

    public function test_it_creates_a_sale_successfully_via_api(): void
    {
        // Inserta un producto inicial en la base de datos
        $product = ProductModel::create([
            'name' => 'Teclado Mecanico',
            'sku' => 'TEC-001',
            'price' => 500.00,
            'stock' => 10,
        ]);

        // Envia una peticion POST a la ruta de la API
        $response = $this->postJson('/api/sales', [
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 2,
                ]
            ]
        ]);

        // Verifica que la respuesta sea HTTP 201 Created
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

        // Confirma que el stock cambio en la base de datos de 10 a 8
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock' => 8,
        ]);
    }
}