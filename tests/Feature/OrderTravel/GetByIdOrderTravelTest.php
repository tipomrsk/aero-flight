<?php

beforeEach(function () {
    $this->token = login();
    $this->withHeader('Authorization', "Bearer {$this->token}");
    $this->withHeader('Accept', "application/json");

    $this->orderTravel = createOrderTravel();
});

it('should get order travel by id', function () {
    $response = $this->get("/api/order-travel/{$this->orderTravel->uuid}");

    $response->assertStatus(200)
        ->assertJson([
            'uuid' => $this->orderTravel->uuid,
            'origin' => $this->orderTravel->origin,
            'destination' => $this->orderTravel->destination,
            'start_date' => $this->orderTravel->start_date,
            'end_date' => $this->orderTravel->end_date,
            'status' => $this->orderTravel->status,
        ]);
});

it('should not get order travel by invalid id', function () {
    $response = $this->get('/api/order-travel/invalidId');

    $response->assertStatus(404)
        ->assertJson(['message' => 'Order travel not found']);
});

it('should not get order travel by id without token', function () {
    $response = $this->withHeader('Authorization', 'Bearer invalidToken')
        ->get("/api/order-travel/{$this->orderTravel->uuid}");

    $response->assertStatus(401)
        ->assertJson(['message' => 'Unauthenticated.']);
});

it('should not get a order of another user', function () {
    login();
    $orderTravel = createOrderTravel([
        'user_id' => 2,
    ]);

    $response = $this->get("/api/order-travel/{$orderTravel->uuid}");

    $response->assertStatus(404)
        ->assertJson(['message' => 'Order travel not found']);
});
