<?php

namespace App\Application\Services;

use App\Domain\Strategies\DiscountStrategy;

class DiscountEngine
{
    /**
     * @var DiscountStrategy[]
     */
    private array $strategies = [];

    /**
     * Registra una nueva estrategia de descuento.
     */
    public function addStrategy(DiscountStrategy $strategy): void
    {
        $this->strategies[] = $strategy;
    }

    /**
     * Aplica todas las estrategias que cumplan los requisitos y calcula el descuento total.
     */
    public function calculateTotalDiscount(array $items, float $totalGross): float
    {
        $totalDiscount = 0.0;

        foreach ($this->strategies as $strategy) {
            if ($strategy->isApplicable($items, $totalGross)) {
                $totalDiscount += $strategy->calculateDiscount($items, $totalGross);
            }
        }

        // El descuento acumulado nunca debe superar el total de la venta
        return min($totalDiscount, $totalGross);
    }
}
