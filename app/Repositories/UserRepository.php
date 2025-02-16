<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    public function __construct(
        protected User $model
    ) {
    }

    public function getUserEmailByOrderUUID(string $uuid): string
    {
        return $this->model->join('order_travel', 'users.id', '=', 'order_travel.user_id')
            ->where('order_travel.uuid', $uuid)
            ->select('users.email')
            ->firstOrFail()
            ->email;
    }
}
