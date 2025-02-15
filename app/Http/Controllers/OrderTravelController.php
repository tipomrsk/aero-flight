<?php

namespace App\Http\Controllers;

use App\Services\OrderTravelService;
use Illuminate\Http\{JsonResponse, Request};
use Illuminate\Support\Facades\Log;

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

            $orderTravels = $this->orderTravelService->getAll($request->all());

            return response()->json($orderTravels);
        } catch (\Exception $e) {
            Log::error([
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
