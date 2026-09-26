<?php

namespace App\Domain\Strategies;

class PercentageDiscountStrategy implements DiscountStrategy
{
    public function __construct(
        private float $percentage,
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

        return $totalGross * ($this->percentage / 100);
    }

    public function getName(): string
    {
        return "Descuento del {$this->percentage}%";
    }
}