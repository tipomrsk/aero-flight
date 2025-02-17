<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use App\Traits\HandlesApiExceptions;
use Illuminate\Http\{JsonResponse, Request};

class AuthController extends Controller
{
    use HandlesApiExceptions;

    public function __construct(
        protected AuthService $authService
    ) {
    }

    /**
     * Undocumented function
     */
    public function login(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            return $this->authService->login($request->email, $request->password);
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Undocumented function
     *
     * @throws \Exception
     */
    public function logout(Request $request): JsonResponse
    {
        try {
            return $this->authService->logout($request);
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }
}
