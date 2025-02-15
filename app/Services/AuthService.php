<?php

namespace App\Services;

use App\Repositories\AuthRepository;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function __construct(
        protected AuthRepository $authRepository
    ) {
    }

    public function login(string $email, string $password): array
    {
        $user = $this->authRepository->getUserByEmail($email);

        if (! $user) {
            throw new \Exception('User not found');
        }

        if (! Hash::check($password, $user->password)) {
            throw new \Exception('Invalid password');
        }

        return [
            'token' => $user->createToken('aero-flight')->plainTextToken,
        ];
    }

    public function logout($request): array
    {
        $request->user()->currentAccessToken()->delete();

        return [
            'message' => 'Logged out',
        ];
    }
}
