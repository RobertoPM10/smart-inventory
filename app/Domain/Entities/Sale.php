<?php

namespace App\Domain\Entities;

class Sale
{
    /**
     * @param SaleItem[] $items
     */
    public function __construct(
        private ?int $id,
        private float $grossTotal,
        private float $discountTotal,
        private float $netTotal,
        private array $items = []
    ) {}

    // Getters de la entidad
    public function getId(): ?int { return $this->id; }
    public function getGrossTotal(): float { return $this->grossTotal; }
    public function getDiscountTotal(): float { return $this->discountTotal; }
    public function getNetTotal(): float { return $this->netTotal; }
    public function getItems(): array { return $this->items; }
}