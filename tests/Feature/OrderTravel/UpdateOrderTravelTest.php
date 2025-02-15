<?php

beforeEach(function () {
    $this->token = login();
    $this->withHeader('Authorization', "Bearer {$this->token}");
    $this->withHeader('Accept', "application/json");

    $this->orderTravel = createOrderTravel();
});

it('should update order travel', function () {
    $response = $this->put("/api/order-travel/{$this->orderTravel->uuid}", [
        'origin' => 'Porto Alegre',
        'destination' => 'São Paulo',
        'start_date' => '2025-04-01',
        'end_date' => '2025-04-10',
    ]);

    $response->assertStatus(200)
        ->assertJson([
            'uuid' => $this->orderTravel->uuid,
            'origin' => 'Porto Alegre',
            'destination' => 'São Paulo',
            'start_date' => '2025-04-01T00:00:00.000000Z',
            'end_date' => '2025-04-10T00:00:00.000000Z',
            'status' => $this->orderTravel->status,
        ]);
});

it('should not update order travel with invalid id', function () {
    $response = $this->put('/api/order-travel/invalidId', [
        'origin' => 'Porto Alegre',
        'destination' => 'São Paulo',
        'start_date' => '2025-04-01',
        'end_date' => '2025-04-10',
    ]);

    $response->assertStatus(500)
        ->assertJson(['message' => 'Order travel not found']);
});

it('should not update order travel without token', function () {
    $response = $this->withHeader('Authorization', "Bearer invalidToken")
        ->put("/api/order-travel/{$this->orderTravel->uuid}", [
            'origin' => 'Porto Alegre',
            'destination' => 'São Paulo',
            'start_date' => '2025-04-01',
            'end_date' => '2025-04-10',
        ]);

    $response->assertStatus(401)
        ->assertJson(['message' => 'Unauthenticated.']);
});

it('should not update order travel of another user', function () {
    login();
    $orderTravel = createOrderTravel(2);

    $response = $this->put("/api/order-travel/{$orderTravel->uuid}", [
        'origin' => 'Porto Alegre',
        'destination' => 'São Paulo',
        'start_date' => '2025-04-01',
        'end_date' => '2025-04-10',
    ]);

    $response->assertStatus(500)
        ->assertJson(['message' => 'Order travel not found']);
});

it('should update order travel without origin', function () {
    $response = $this->put("/api/order-travel/{$this->orderTravel->uuid}", [
        'destination' => 'São Paulo',
        'start_date' => '2025-04-01',
        'end_date' => '2025-04-10',
    ]);

    $response->assertStatus(200)
        ->assertJson([
            'uuid' => $this->orderTravel->uuid,
            'origin' => $this->orderTravel->origin,
            'destination' => 'São Paulo',
            'start_date' => '2025-04-01T00:00:00.000000Z',
            'end_date' => '2025-04-10T00:00:00.000000Z',
            'status' => $this->orderTravel->status,
        ]);
});

it('should update order travel without destination', function () {
    $response = $this->put("/api/order-travel/{$this->orderTravel->uuid}", [
        'origin' => 'Porto Alegre',
        'start_date' => '2025-04-01',
        'end_date' => '2025-04-10',
    ]);

    $response->assertStatus(200)
        ->assertJson([
            'uuid' => $this->orderTravel->uuid,
            'origin' => 'Porto Alegre',
            'destination' => $this->orderTravel->destination,
            'start_date' => '2025-04-01T00:00:00.000000Z',
            'end_date' => '2025-04-10T00:00:00.000000Z',
            'status' => $this->orderTravel->status,
        ]);
});
