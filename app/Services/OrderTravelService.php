<?php

namespace App\Services;

use App\Repositories\OrderTravelRepository;

class OrderTravelService
{
    protected $userId;

    public function __construct(
        protected OrderTravelRepository $repository
    ) {
        $this->userId = auth()->user()->id;
    }

    public function getAll(array $filters): array
    {
        return $this->repository->getAll($filters, $this->userId);
    }
}
