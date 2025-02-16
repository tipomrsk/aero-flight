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

    public function store(array $data): array
    {
        return $this->repository->store($data, $this->userId);
    }

    public function show(string $uuid): array
    {
        return $this->repository->show($uuid, $this->userId);
    }

    public function update(string $uuid, array $data): array
    {
        return $this->repository->update($uuid, $data, $this->userId);
    }

    public function updateStatus(string $uuid, string $status): array
    {
        return $this->repository->updateStatus($uuid, $status);
    }

    public function destroy(string $uuid): array
    {
        return $this->repository->destroy($uuid, $this->userId);
    }
}
