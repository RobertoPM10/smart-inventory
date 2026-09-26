<?php

namespace App\Domain\Entities;

use InvalidArgumentException;

class Product
{
    public function __construct(
        private ?int $id,
        private string $name,
        private string $sku,
        private float $price,
        private int $stock
    ) {
        if ($this->price < 0) {
            throw new InvalidArgumentException("El precio no puede ser negativo.");
        }

        if ($this->stock < 0) {
            throw new InvalidArgumentException("El stock no puede ser negativo.");
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSku(): string
    {
        return $this->sku;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getStock(): int
    {
        return $this->stock;
    }

    /**
     * Regla de negocio: Reducir stock al realizar una venta.
     */
    public function decreaseStock(int $quantity): void
    {
        if ($quantity <= 0) {
            throw new InvalidArgumentException("La cantidad a reducir debe ser mayor a cero.");
        }

        if ($quantity > $this->stock) {
            throw new InvalidArgumentException("Stock insuficiente para el producto {$this->name}.");
        }

        $this->stock -= $quantity;
    }

    /**
     * Regla de negocio: Aumentar stock al recibir inventario.
     */
    public function increaseStock(int $quantity): void
    {
        if ($quantity <= 0) {
            throw new InvalidArgumentException("La cantidad a agregar debe ser mayor a cero.");
        }

        $this->stock += $quantity;
    }
}