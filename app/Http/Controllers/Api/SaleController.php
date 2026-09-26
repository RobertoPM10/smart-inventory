<?php

namespace App\Http\Controllers\Api;

use App\Application\UseCases\CreateSaleUseCase;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSaleRequest;
use Illuminate\Http\JsonResponse;
use InvalidArgumentException;

class SaleController extends Controller
{
    // Inyectamos el caso de uso directamente en el controlador
    public function __construct(
        private CreateSaleUseCase $createSaleUseCase
    ) {}

    public function store(StoreSaleRequest $request): JsonResponse
    {
        try {
            // Ejecutamos la logica de negocio con los datos validados
            $result = $this->createSaleUseCase->execute(
                $request->validated('items')
            );

            return response()->json([
                'message' => 'Venta registrada con éxito.',
                'data' => $result
            ], 201);

        } catch (InvalidArgumentException $e) {
            // Capta errores de regla de negocio (ej. stock insuficiente)
            return response()->json([
                'error' => $e->getMessage()
            ], 422);
        }
    }
}