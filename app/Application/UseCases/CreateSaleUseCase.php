<?php

namespace App\Application\UseCases;

use App\Application\Services\DiscountEngine;
use App\Domain\Entities\Sale;
use App\Domain\Entities\SaleItem;
use App\Domain\Repositories\ProductRepositoryInterface;
use App\Domain\Repositories\SaleRepositoryInterface;
use InvalidArgumentException;

class CreateSaleUseCase
{
    // Inyectamos ambos repositorios mediante Inversión de Dependencias (SOLID)
    public function __construct(
        private ProductRepositoryInterface $productRepository,
        private SaleRepositoryInterface $saleRepository,
        private DiscountEngine $discountEngine
    ) {}

    public function execute(array $items): array
    {
        if (empty($items)) {
            throw new InvalidArgumentException("La venta debe contener al menos un producto.");
        }

        $grossTotal = 0.0;
        $processedProducts = [];
        $domainSaleItems = [];

        // 1. Validar productos, calcular sublocales y descontar stock
        foreach ($items as $item) {
            $productId = $item['product_id'];
            $quantity = $item['quantity'];

            $product = $this->productRepository->findById($productId);

            if (!$product) {
                throw new InvalidArgumentException("El producto con ID {$productId} no existe.");
            }

            // Descontar stock en el modelo de dominio
            $product->decreaseStock($quantity);

            $unitPrice = $product->getPrice();
            $subtotal = $unitPrice * $quantity;
            $grossTotal += $subtotal;

            $processedProducts[] = $product;

            // Instanciar entidad de dominio SaleItem
            $domainSaleItems[] = new SaleItem(
                id: null,
                productId: $productId,
                quantity: $quantity,
                unitPrice: $unitPrice,
                subtotal: $subtotal
            );
        }

        // 2. Aplicar motor de descuentos
        $discountTotal = $this->discountEngine->calculateTotalDiscount($items, $grossTotal);
        $netTotal = $grossTotal - $discountTotal;

        // 3. Persistir cambios de stock de los productos
        foreach ($processedProducts as $product) {
            $this->productRepository->save($product);
        }

        // 4. Crear y guardar el registro histórico de la venta
        $sale = new Sale(
            id: null,
            grossTotal: $grossTotal,
            discountTotal: $discountTotal,
            netTotal: $netTotal,
            items: $domainSaleItems
        );

        $savedSale = $this->saleRepository->save($sale);

        // 5. Retornar respuesta
        return [
            'sale_id' => $savedSale->getId(),
            'gross_total' => $savedSale->getGrossTotal(),
            'discount_total' => $savedSale->getDiscountTotal(),
            'net_total' => $savedSale->getNetTotal(),
            'items_count' => count($savedSale->getItems()),
        ];
    }
}