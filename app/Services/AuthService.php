<?php

namespace App\Services;

use App\Repositories\AuthRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function __construct(
        protected AuthRepository $authRepository
    ) {
    }

    /**
     * Login user
     *
     * @param string $email
     * @param string $password
     * @return JsonResponse
     */
    public function login(string $email, string $password): JsonResponse
    {
        $user = $this->authRepository->getUserByEmail($email);

        if (! $user) {
            throw new \Exception('User not found');
        }

        if (! Hash::check($password, $user->password)) {
            throw new \Exception('Invalid password');
        }

        return response()->json([
            'token' => $user->createToken('auth_token')->plainTextToken,
        ], JsonResponse::HTTP_OK);
    }

    /**
     * Logout user
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logout($request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out',
        ], JsonResponse::HTTP_OK);
    }
}
