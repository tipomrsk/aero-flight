<?php

namespace App\Http\Controllers;

use App\Services\OrderTravelService;
use App\Traits\HandlesApiExceptions;
use Illuminate\Http\{JsonResponse, Request, Response};

class OrderTravelController extends Controller
{
    use HandlesApiExceptions;

    public function __construct(
        protected OrderTravelService $orderTravelService
    ) {
    }

    /**
     * Get all order travels
     */
    public function getAll(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'start_at' => 'required|date',
                'end_at' => 'date|after_or_equal:start_at',
                'status' => 'required|string|in:pending,approved,canceled',
                'destiny' => 'string',
            ]);

            return response()->json(
                $this->orderTravelService->getAll($request->all()),
                Response::HTTP_OK
            );
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Store a new order travel
     */
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
            return $this->handleException($e);
        }
    }

    /**
     * Get order travel by uuid
     */
    public function show(string $orderTravel): JsonResponse
    {
        try {
            return response()->json(
                $this->orderTravelService->show($orderTravel),
                Response::HTTP_OK
            );
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Update order travel by uuid
     */
    public function update(Request $request, string $orderTravel): JsonResponse
    {
        try {
            $request->validate([
                'origin' => 'string',
                'destination' => 'string',
                'start_date' => 'date',
                'end_date' => 'date|after:start_date',
            ]);

            if ($request->has('status')) {
                throw new \Exception('Status cannot be updated');
            }

            $requested = $request->only(['origin', 'destination', 'start_date', 'end_date']);

            if (count($requested) === 0) {
                throw new \Exception('Incorret payload');
            }

            return response()->json(
                $this->orderTravelService->update($orderTravel, $request->all()),
                Response::HTTP_OK
            );
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Update order travel status by uuid
     */
    public function updateStatus(Request $request, string $orderTravel): JsonResponse
    {
        try {
            if (! $request->user()->is_admin) {
                return response()->json([
                    'message' => 'Unauthorized',
                ], Response::HTTP_FORBIDDEN);
            }

            $request->validate([
                'status' => 'required|string|in:approved,canceled',
            ]);

            if (count($request->all()) > 1) {
                throw new \Exception('Only status can be updated');
            }

            return response()->json(
                $this->orderTravelService->updateStatus($orderTravel, $request->status),
                Response::HTTP_OK
            );
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Destroy order travel by uuid
     */
    public function destroy(Request $request, string $orderTravel): JsonResponse
    {
        try {
            if (! $request->user()->is_admin) {
                return response()->json([
                    'message' => 'Unauthorized',
                ], Response::HTTP_FORBIDDEN);
            }

            return response()->json(
                $this->orderTravelService->destroy($orderTravel),
                Response::HTTP_OK
            );
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }
}
