<?php

namespace App\Repositories;

use App\Models\User;

class AuthRepository
{
    public function __construct(
        protected User $model
    ) {
    }

    public function getUserByEmail(string $email): ?User
    {
        return $this->model->where('email', $email)->first();
    }
}
