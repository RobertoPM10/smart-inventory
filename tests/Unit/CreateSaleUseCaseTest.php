<?php

namespace Tests\Unit;

use App\Application\Services\DiscountEngine;
use App\Application\UseCases\CreateSaleUseCase;
use App\Domain\Entities\Product;
use App\Domain\Repositories\ProductRepositoryInterface;
use App\Domain\Strategies\PercentageDiscountStrategy;
use PHPUnit\Framework\TestCase;

class CreateSaleUseCaseTest extends TestCase
{
    public function test_it_executes_sale_and_updates_stock_correctly(): void
    {
        // Crear producto con precio 100 y stock 5
        $product = new Product(1, 'Mouse', 'MS-01', 100.0, 5);

        // Simular el repositorio para no tocar la base de datos real
        $repository = $this->createMock(ProductRepositoryInterface::class);
        $repository->method('findById')->willReturn($product);
        $repository->expects($this->once())->method('save');

        // Configurar el motor con 10% de descuento (mínimo $100)
        $engine = new DiscountEngine();
        $engine->addStrategy(new PercentageDiscountStrategy(10, 100));

        // Ejecutar el caso de uso vendiendo 2 unidades
        $useCase = new CreateSaleUseCase($repository, $engine);
        $result = $useCase->execute([
            ['product_id' => 1, 'quantity' => 2]
        ]);

        // Validaciones de negocio
        $this->assertEquals(200.0, $result['gross_total']); // 2 x $100 = $200
        $this->assertEquals(20.0, $result['discount_total']); // 10% de $200 = $20
        $this->assertEquals(180.0, $result['net_total']); // Total con descuento
        $this->assertEquals(3, $product->getStock()); // El stock bajó de 5 a 3
    }
}