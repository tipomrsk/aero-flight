<?php

namespace App\Http\Controllers;

use App\Services\OrderTravelService;
use Illuminate\Http\{JsonResponse, Request, Response};
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class OrderTravelController extends Controller
{
    public function __construct(
        protected OrderTravelService $orderTravelService
    ) {
    }

    public function getAll(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'start_at' => 'required|date',
                'end_at' => 'date|after_or_equal:start_at',
                'status' => 'required|string',
                'destiny' => 'string',
            ]);

            return response()->json(
                $this->orderTravelService->getAll($request->all()),
                Response::HTTP_OK
            );
        } catch (ValidationException $e) {
            Log::error('Validation error: ' . $e->getMessage(), $e->errors());

            return response()->json([
                'errors' => $e->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage());

            return response()->json([
                'message' => 'An unexpected error occurred. Please try again later.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'origin' => 'required|string',
                'destination' => 'required|string',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after:start_date',
            ]);

            return response()->json(
                $this->orderTravelService->store($request->all()),
                Response::HTTP_CREATED
            );
        } catch (\Exception $e) {
            Log::error([
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(string $orderTravel): JsonResponse
    {
        try {
            return response()->json(
                $this->orderTravelService->show($orderTravel),
                Response::HTTP_OK
            );
        } catch (\Exception $e) {
            Log::error([
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Order travel not found',
            ], Response::HTTP_NOT_FOUND);
        }
    }
}
