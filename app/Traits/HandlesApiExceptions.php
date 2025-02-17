<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

trait HandlesApiExceptions
{
    protected function handleException(\Throwable $e): JsonResponse
    {
        if ($e instanceof ValidationException) {
            Log::error('Validation error: ' . $e->getMessage(), $e->errors());

            return response()->json([
                'errors' => $e->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return response()->json([
            'message' => $e->getMessage(),
        ], $e->getCode() ?: Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
