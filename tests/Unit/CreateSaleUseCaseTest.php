<?php

namespace Tests\Unit;

use App\Application\Services\DiscountEngine;
use App\Application\UseCases\CreateSaleUseCase;
use App\Domain\Entities\Product;
use App\Domain\Entities\Sale;
use App\Domain\Repositories\ProductRepositoryInterface;
use App\Domain\Repositories\SaleRepositoryInterface;
use App\Domain\Strategies\PercentageDiscountStrategy;
use PHPUnit\Framework\TestCase;

class CreateSaleUseCaseTest extends TestCase
{
    public function test_it_executes_sale_and_persists_data_correctly(): void
    {
        // Producto inicial
        $product = new Product(1, 'Mouse', 'MS-01', 100.0, 5);

        // Mock del repositorio de productos
        $productRepository = $this->createMock(ProductRepositoryInterface::class);
        $productRepository->method('findById')->willReturn($product);
        $productRepository->expects($this->once())->method('save');

        // Mock del repositorio de ventas
        $saleRepository = $this->createMock(SaleRepositoryInterface::class);
        $saleRepository->method('save')->willReturnCallback(function (Sale $sale) {
            return new Sale(10, $sale->getGrossTotal(), $sale->getDiscountTotal(), $sale->getNetTotal(), $sale->getItems());
        });

        // Configurar motor de descuentos
        $engine = new DiscountEngine();
        $engine->addStrategy(new PercentageDiscountStrategy(10, 100));

        // Ejecutar caso de uso
        $useCase = new CreateSaleUseCase($productRepository, $saleRepository, $engine);
        $result = $useCase->execute([
            ['product_id' => 1, 'quantity' => 2]
        ]);

        // Verificaciones
        $this->assertEquals(10, $result['sale_id']);
        $this->assertEquals(200.0, $result['gross_total']);
        $this->assertEquals(20.0, $result['discount_total']);
        $this->assertEquals(180.0, $result['net_total']);
        $this->assertEquals(3, $product->getStock());
    }
}