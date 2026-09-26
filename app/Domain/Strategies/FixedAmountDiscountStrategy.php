<?php

namespace App\Domain\Strategies;

class FixedAmountDiscountStrategy implements DiscountStrategy
{
    public function __construct(
        private float $discountAmount,
        private float $minimumAmount = 0.0
    ) {}

    public function isApplicable(array $items, float $totalGross): bool
    {
        return $totalGross >= $this->minimumAmount;
    }

    public function calculateDiscount(array $items, float $totalGross): float
    {
        if (!$this->isApplicable($items, $totalGross)) {
            return 0.0;
        }

        // El descuento no puede ser mayor que la compra misma
        return min($this->discountAmount, $totalGross);
    }

    public function getName(): string
    {
        return "Descuento fijo de $" . number_format($this->discountAmount, 2);
    }
}