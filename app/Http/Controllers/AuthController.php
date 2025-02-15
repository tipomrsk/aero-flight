<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use Illuminate\Http\{JsonResponse, Request, Response};
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function __construct(
        protected AuthService $authService
    ) {
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            return $this->authService->login($request->email, $request->password);
        } catch (ValidationException $e) {
            Log::error([
                'message' => $e->getMessage(),
                'errors' => $e->errors(),
            ]);

            return response()->json([
                'errors' => $e->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Exception $e) {
            Log::error([
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Invalid credentials',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function logout(Request $request): JsonResponse
    {
        try {
            return $this->authService->logout($request);
        } catch (\Exception $e) {
            Log::error([
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Unable to logout',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
