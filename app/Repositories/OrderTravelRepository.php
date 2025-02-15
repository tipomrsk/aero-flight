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

        throw new \Exception('Error');

        return $query->select('uuid', 'origin', 'destination', 'start_date', 'end_date', 'status')
            ->get()->toArray();
    }
}
