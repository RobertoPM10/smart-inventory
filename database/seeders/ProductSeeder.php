<?php

namespace Database\Seeders;

use App\Models\ProductModel;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        ProductModel::create([
            'name' => 'Laptop ThinkPad X1',
            'sku' => 'THINK-X1',
            'price' => 15000.00,
            'stock' => 10,
        ]);

        ProductModel::create([
            'name' => 'Teclado Mecánico RGB',
            'sku' => 'TEC-RGB-01',
            'price' => 1200.00,
            'stock' => 25,
        ]);

        ProductModel::create([
            'name' => 'Mouse Inalámbrico',
            'sku' => 'MOU-WL-02',
            'price' => 450.00,
            'stock' => 15,
        ]);

        ProductModel::create([
            'name' => 'Monitor 27" 4K',
            'sku' => 'MON-27-4K',
            'price' => 6500.00,
            'stock' => 5,
        ]);
    }
}