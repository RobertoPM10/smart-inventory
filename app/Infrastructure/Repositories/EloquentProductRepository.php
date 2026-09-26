<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Entities\Product;
use App\Domain\Repositories\ProductRepositoryInterface;
use App\Models\ProductModel;

class EloquentProductRepository implements ProductRepositoryInterface
{
    public function findById(int $id): ?Product
    {
        $model = ProductModel::find($id);

        if (!$model) {
            return null;
        }

        return $this->toEntity($model);
    }

    public function findBySku(string $sku): ?Product
    {
        $model = ProductModel::where('sku', $sku)->first();

        if (!$model) {
            return null;
        }

        return $this->toEntity($model);
    }

    public function all(): array
    {
        return ProductModel::all()
            ->map(fn (ProductModel $model) => $this->toEntity($model))
            ->toArray();
    }

    public function save(Product $product): Product
    {
        $model = ProductModel::updateOrCreate(
            ['id' => $product->getId()],
            [
                'name' => $product->getName(),
                'sku' => $product->getSku(),
                'price' => $product->getPrice(),
                'stock' => $product->getStock(),
            ]
        );

        return $this->toEntity($model);
    }

    public function delete(int $id): bool
    {
        return ProductModel::destroy($id) > 0;
    }

    /**
     * Mapea un modelo de Eloquent a una entidad pura de Dominio.
     */
    private function toEntity(ProductModel $model): Product
    {
        return new Product(
            id: $model->id,
            name: $model->name,
            sku: $model->sku,
            price: (float) $model->price,
            stock: (int) $model->stock
        );
    }
}