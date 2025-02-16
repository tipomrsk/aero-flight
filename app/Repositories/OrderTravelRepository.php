<?php

namespace App\Repositories;

use App\Models\OrderTravel;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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

    /**
     * Update a order travel by uuid
     *
     * @param string $uuid
     * @param array $data
     * @param integer $userId
     * @return array
     */
    public function update(string $uuid, array $data, int $userId): array
    {
        try {
            $orderTravel = $this->model->where('uuid', $uuid)
                ->where('user_id', $userId)
                ->firstOrFail();

            $orderTravel->update($data);

            return $orderTravel->toArray();
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            throw new \Exception('Order travel not found');
        } catch (\Exception $e) {
            throw new \Exception('Error updating order travel status');
        }
    }

    /**
     * Update a order travel status by uuid
     *
     * @param string $uuid
     * @param string $status
     * @return array
     */
    public function updateStatus(string $uuid, string $status): array
    {
        try {
            $orderTravel = $this->model->where('uuid', $uuid)
                ->where('status', '!=', 'approved')
                ->firstOrFail();

            $orderTravel->update(['status' => $status]);

            return $orderTravel->toArray();
        } catch (ModelNotFoundException $e) {
            throw new \Exception('Order travel not found', 404);
        } catch (\Exception $e) {
            throw new \Exception('Error updating order travel status', 500);
        }
    }

    /**
     * Delete a order travel by uuid
     *
     * @param string $uuid
     * @return array
     */
    public function destroy(string $uuid): array
    {
        try {
            $order = $this->model->where('uuid', $uuid)
                ->firstOrFail();

            $order->delete();

            return ['message' => 'Order travel deleted'];
        } catch (ModelNotFoundException $e) {
            throw new \Exception('Order travel not found', 404);
        } catch (\Exception $e) {
            throw new \Exception('Error deleting order travel', 500);
        }
    }
}
