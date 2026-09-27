<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Sale;

interface SaleRepositoryInterface
{
    // Guarda el agregado Sale completo con sus items en la base de datos
    public function save(Sale $sale): Sale;
}