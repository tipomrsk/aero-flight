<?php

namespace App\Repositories;

use App\Models\OrderTravel;

class OrderTravelRepository
{
    public function __construct(
        protected OrderTravel $model
    ) {
    }

    /**
     * Get all orders travels by filters
     *
     * @param array $filters
     * @param integer $userId
     * @return array
     */
    public function getAll(array $filters, int $userId): array
    {
        $query = $this->model->query();

        if (isset($filters['start_at'])) {
            $query->where('start_date', '>=', $filters['start_at']);
        }

        if (isset($filters['end_at'])) {
            $query->where('end_date', '<=', $filters['end_at']);
        }

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['destiny'])) {
            $query->where('destination', 'like', "%{$filters['destiny']}%");
        }

        $query->where('user_id', $userId);

        return $query->select('uuid', 'origin', 'destination', 'start_date', 'end_date', 'status')
            ->get()->toArray();
    }

    /**
     * Store a new order travel
     *
     * @param array $data
     * @param integer $userId
     * @return array
     */
    public function store(array $data, int $userId): array
    {
        $data['user_id'] = $userId;

        return $this->model->create($data)->toArray();
    }

    /**
     * Get a order travel by uuid
     *
     * @param string $uuid
     * @param integer $userId
     * @return array
     */
    public function show(string $uuid, int $userId): array
    {
        return $this->model->where('uuid', $uuid)
            ->where('user_id', $userId)
            ->select('uuid', 'origin', 'destination', 'start_date', 'end_date', 'status')
            ->firstOrFail()
            ->toArray();
    }
}
