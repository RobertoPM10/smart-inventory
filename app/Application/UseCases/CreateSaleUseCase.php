<?php

namespace App\Application\UseCases;

use App\Application\Services\DiscountEngine;
use App\Domain\Repositories\ProductRepositoryInterface;
use InvalidArgumentException;

class CreateSaleUseCase
{
    // Inyectamos el repositorio abstracto y el motor de descuentos.
    // Esto respeta el principio de Inversión de Dependencias (D de SOLID).
    public function __construct(
        private ProductRepositoryInterface $productRepository,
        private DiscountEngine $discountEngine
    ) {}

    /**
     * Ejecuta el proceso completo de registro de venta.
     * 
     * @param array $items Estructura esperada: [['product_id' => 1, 'quantity' => 2], ...]
     * @return array Resumen financiero de la venta realizada.
     */
    public function execute(array $items): array
    {
        if (empty($items)) {
            throw new InvalidArgumentException("La venta debe contener al menos un producto.");
        }

        $grossTotal = 0.0;
        $processedProducts = [];

        // 1. Fase de Validación y Cálculo Bruto
        foreach ($items as $item) {
            $productId = $item['product_id'];
            $quantity = $item['quantity'];

            // Buscamos el producto mediante la interfaz del repositorio
            $product = $this->productRepository->findById($productId);

            if (!$product) {
                throw new InvalidArgumentException("El producto con ID {$productId} no existe.");
            }

            // Regla de negocio: La entidad valida y reduce su propio stock interno
            $product->decreaseStock($quantity);

            // Sumamos al total bruto acumulado
            $grossTotal += $product->getPrice() * $quantity;

            // Guardamos la referencia para actualizar persisntencia al final
            $processedProducts[] = $product;
        }

        // 2. Fase de Aplicación de Reglas de Descuento
        // El motor evalúa las estrategias configuradas sobre el listado y monto bruto
        $discountTotal = $this->discountEngine->calculateTotalDiscount($items, $grossTotal);

        // 3. Fase de Persistencia (Guardar cambios de stock)
        foreach ($processedProducts as $product) {
            $this->productRepository->save($product);
        }

        // 4. Retornamos la estructura de confirmación
        return [
            'gross_total' => $grossTotal,
            'discount_total' => $discountTotal,
            'net_total' => $grossTotal - $discountTotal,
            'items_count' => count($items),
        ];
    }
}
