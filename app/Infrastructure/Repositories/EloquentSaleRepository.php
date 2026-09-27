<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Entities\Sale as DomainSale;
use App\Domain\Repositories\SaleRepositoryInterface;
use App\Models\Sale as SaleModel;

class EloquentSaleRepository implements SaleRepositoryInterface
{
    public function save(DomainSale $sale): DomainSale
    {
        $saleModel = SaleModel::create([
            'gross_total' => $sale->getGrossTotal(),
            'discount_total' => $sale->getDiscountTotal(),
            'net_total' => $sale->getNetTotal(),
        ]);

        foreach ($sale->getItems() as $item) {
            $saleModel->items()->create([
                'product_id' => $item->getProductId(),
                'quantity' => $item->getQuantity(),
                'unit_price' => $item->getUnitPrice(),
                'subtotal' => $item->getSubtotal(),
            ]);
        }

        return new DomainSale(
            id: $saleModel->id,
            grossTotal: (float) $saleModel->gross_total,
            discountTotal: (float) $saleModel->discount_total,
            netTotal: (float) $saleModel->net_total,
            items: $sale->getItems()
        );
    }
}