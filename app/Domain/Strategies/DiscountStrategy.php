<?php

namespace App\Domain\Strategies;

interface DiscountStrategy
{
    /**
     * Evalúa si la regla de descuento aplica según los productos e importe actual.
     * 
     * @param array $items Lista de productos en la venta.
     * @param float $totalGross Monto total bruto antes de descuentos.
     * @return bool
     */
    public function isApplicable(array $items, float $totalGross): bool;

    /**
     * Calcula la cantidad exacta a descontar.
     * 
     * @param array $items Lista de productos en la venta.
     * @param float $totalGross Monto total bruto antes de descuentos.
     * @return float
     */
    public function calculateDiscount(array $items, float $totalGross): float;

    /**
     * Devuelve la descripción legible de la regla de descuento.
     * 
     * @return string
     */
    public function getName(): string;
}