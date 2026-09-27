<?php

namespace App\Domain\Entities;

class SaleItem
{
    public function __construct(
        private ?int $id,
        private int $productId,
        private int $quantity,
        private float $unitPrice,
        private float $subtotal
    ) {}

    // Getters de la entidad
    public function getId(): ?int { return $this->id; }
    public function getProductId(): int { return $this->productId; }
    public function getQuantity(): int { return $this->quantity; }
    public function getUnitPrice(): float { return $this->unitPrice; }
    public function getSubtotal(): float { return $this->subtotal; }
}