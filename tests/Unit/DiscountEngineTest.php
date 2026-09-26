<?php

namespace Tests\Unit;

use App\Application\Services\DiscountEngine;
use App\Domain\Strategies\FixedAmountDiscountStrategy;
use App\Domain\Strategies\PercentageDiscountStrategy;
use PHPUnit\Framework\TestCase;

class DiscountEngineTest extends TestCase
{
    public function test_it_calculates_percentage_discount_correctly(): void
    {
        // 1. Preparación (Arrange)
        $engine = new DiscountEngine();
        $engine->addStrategy(new PercentageDiscountStrategy(percentage: 10, minimumAmount: 100));

        $items = [];
        $totalGross = 200.0;

        // 2. Ejecución (Act)
        $discount = $engine->calculateTotalDiscount($items, $totalGross);

        // 3. Aserción / Verificación (Assert)
        // 10% de 200 debe ser 20
        $this->assertEquals(20.0, $discount);
    }

    public function test_it_does_not_apply_discount_if_minimum_amount_is_not_met(): void
    {
        $engine = new DiscountEngine();
        $engine->addStrategy(new PercentageDiscountStrategy(percentage: 10, minimumAmount: 100));

        $items = [];
        $totalGross = 50.0; // No alcanza el mínimo de 100

        $discount = $engine->calculateTotalDiscount($items, $totalGross);

        $this->assertEquals(0.0, $discount);
    }

    public function test_it_combines_multiple_discount_strategies(): void
    {
        $engine = new DiscountEngine();
        
        // Estrategia 1: 10% de descuento (mínimo 100)
        $engine->addStrategy(new PercentageDiscountStrategy(percentage: 10, minimumAmount: 100));
        
        // Estrategia 2: $15 fijos de descuento (mínimo 50)
        $engine->addStrategy(new FixedAmountDiscountStrategy(discountAmount: 15, minimumAmount: 50));

        $items = [];
        $totalGross = 200.0;

        // 10% de 200 = 20, más $15 fijos = 35 de descuento total
        $discount = $engine->calculateTotalDiscount($items, $totalGross);

        $this->assertEquals(35.0, $discount);
    }
}