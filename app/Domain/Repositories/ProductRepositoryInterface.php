<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Product;

interface ProductRepositoryInterface
{
    /**
     * Busca un producto por su ID único.
     */
    public function findById(int $id): ?Product;

    /**
     * Busca un producto por su código SKU.
     */
    public function findBySku(string $sku): ?Product;

    /**
     * Retorna todos los productos.
     * 
     * @return Product[]
     */
    public function all(): array;

    /**
     * Guarda o actualiza un producto en el almacenamiento.
     */
    public function save(Product $product): Product;

    /**
     * Elimina un producto por su ID.
     */
    public function delete(int $id): bool;
}