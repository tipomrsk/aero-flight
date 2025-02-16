<?php

namespace App\Services;

use App\Mail\ApprovedOrderTravel;
use App\Repositories\{OrderTravelRepository, UserRepository};
use Illuminate\Support\Facades\Mail;

class OrderTravelService
{
    protected $userId;

    public function __construct(
        protected OrderTravelRepository $repository,
        protected UserRepository $userRepository
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
        $update = $this->repository->updateStatus($uuid, $status);

        if ($status === 'approved') {
            Mail::to(
                $this->userRepository->getUserEmailByOrderUUID($uuid)
            )->send(new ApprovedOrderTravel());
        }

        return $update;
    }

    public function destroy(string $uuid): array
    {
        return $this->repository->destroy($uuid);
    }
}
