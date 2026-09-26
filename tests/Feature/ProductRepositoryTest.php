<?php

namespace Tests\Feature;

use App\Domain\Entities\Product;
use App\Domain\Repositories\ProductRepositoryInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_save_and_find_a_product_in_database(): void
    {
        // Inyectamos la interfaz desde el contenedor de dependencias de Laravel.
        /** @var ProductRepositoryInterface $repository */
        $repository = $this->app->make(ProductRepositoryInterface::class);

        // Creamos una entidad pura de Dominio en memoria 
        $product = new Product(
            id: null,
            name: 'Laptop ThinkPad',
            sku: 'THINK-001',
            price: 15000.00,
            stock: 10
        );

        // Guarda la entidad en la base de datos usando nuestro repositorio
        $savedProduct = $repository->save($product);

        // Comprueba que la base de datos le asigno un ID autoincrementable
        $this->assertNotNull($savedProduct->getId());
        $this->assertEquals('Laptop ThinkPad', $savedProduct->getName());

        // Consulta la base de datos usando el ID asignado para confirmar la persistencia real
        $foundProduct = $repository->findById($savedProduct->getId());

        // Confirmamos que el producto recuperado coincide exactamente con lo guardado
        $this->assertNotNull($foundProduct);
        $this->assertEquals('THINK-001', $foundProduct->getSku());
        $this->assertEquals(15000.00, $foundProduct->getPrice());
        $this->assertEquals(10, $foundProduct->getStock());
    }
}