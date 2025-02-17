<?php

namespace App\Repositories;

use App\Models\User;

class AuthRepository
{
    public function __construct(
        protected User $model
    ) {
    }

    /**
     * Get user by email
     */
    public function getUserByEmail(string $email): ?User
    {
        return $this->model->where('email', $email)->first();
    }
}
